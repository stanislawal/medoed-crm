<?php

namespace App\Http\Controllers\Report;

use app\Constants\DocumentTypeConstants;
use App\Helpers\DocumentHelper;
use App\Http\Controllers\Controller;
use App\Mail\DocumentMail;
use App\Models\Article;
use App\Models\AuthorPayment\AuthorPayment;
use App\Models\Bank;
use App\Models\CrossDocumentReportArticle;
use App\Models\DocumentReport;
use App\Models\Rate\Rate;
use App\Models\User;
use App\Repositories\Report\AuthorRepositories;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ReportAuthorController extends Controller
{

    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $diffInWeekdays = Carbon::parse($startDate)->diffInWeekdays(Carbon::parse($endDate)) + 1;
        $diffInCurrentDay = Carbon::parse($startDate)->diffInWeekdays(Carbon::parse(now())) + 1;

        $reports = AuthorRepositories::getReport($request, $startDate, $endDate,
            $diffInWeekdays)
            ->paginate(50);

        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        $indicators = AuthorRepositories::getReport($request, $startDate, $endDate, $diffInWeekdays);

        $indicators = User::on()->selectRaw("
            sum(authors.margin) as margin,
            sum(authors.without_space) as without_space,
            sum(authors.amount) as amount,
            sum(authors.gross_income) as gross_income,
            sum(authors.duty) as duty,
            sum(authors.payment_amount) as payment_amount,
            sum(authors.working_day) as working_day,
            (sum(if(authors.is_work, authors.without_space, 0)) / {$diffInCurrentDay}) as without_space_in_day
        ")->fromSub($indicators, 'authors')
            ->first()
            ->toArray();

        $remainderDuty = AuthorRepositories::getDuty(Carbon::parse($startDate)->subDay(), $request->author_id, $request)
            ->get()
            ->toArray();

        return view('report.author.author_list', [
            'rates'            => Rate::on()->get(),
            'reports'          => $reports,
            'indicators'       => $indicators,
            'diffInWeekdays'   => $diffInWeekdays,
            'diffInCurrentDay' => $diffInCurrentDay,
            'authors'          => $authors,
            'remainderDuty'    => collect($remainderDuty),
            'banks'            => Bank::on()->get(),
        ]);
    }

    /**
     * Возвращает отчет по указанному автору
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, $id)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $ignoreArticleList = AuthorRepositories::getIgnoreArticles($startDate, $endDate, $id)->get()->toArray();

        $articles = AuthorRepositories::getReportByAuthor($startDate, $endDate, $id)->paginate(50);

        $indicators = AuthorRepositories::getReportByAuthor($startDate, $endDate, $id);

        $indicators = Article::on()->selectRaw("
            sum(report.without_space_author) as without_space_author,
            sum(report.price) as price,
            sum(report.price_article) as price_article,
            sum(report.margin) as margin,
            sum(report.payment_amount) as payment_amount,
            (sum(report.price) - sum(report.payment_amount)) as duty
        ")->fromSub($indicators, 'report')
            ->first()
            ->toArray();

        $user = User::on()
            ->selectRaw("
                users.id,
                users.full_name,
                users.payment,
                banks.name as bank,
                users.duty,
                email_for_doc
            ")
            ->from('users')
            ->leftJoin('banks', 'banks.id', '=', 'users.bank_id')
            ->where('users.id', $id)
            ->get()
            ->first()
            ->toArray();

        $remainderDuty = AuthorRepositories::getDuty(Carbon::parse($startDate)->subDay(), $id)->first()->remainder_duty ?? 0;

        // получить историю оплат
        $paymentHistory = AuthorPayment::on()->where('author_id', $id)->whereBetween('date', [
            $startDate, $endDate
        ])->orderByDesc('id')->get()->toArray();

        // в показатели добавляем сумму оплат из истории оплат
        $indicators['payment_amount'] = $indicators['payment_amount'] + (collect($paymentHistory)->sum('amount'));
        $indicators['duty'] = $indicators['duty'] - (collect($paymentHistory)->sum('amount'));


        $documents = DocumentReport::on()->where('author_id', $id)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                Carbon::parse($endDate)->endOfDay()->toDateTimeString(),
            ])->orderByDesc('id')->get();

        return view('report.author.author_item', [
            'articles'          => $articles,
            'user'              => $user,
            'indicators'        => $indicators,
            'remainderDuty'     => $remainderDuty,
            'ignoreArticleList' => $ignoreArticleList,
            'paymentHistory'    => $paymentHistory,
            'documents'         => $documents,
        ]);
    }

    public function getArticleList(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date',
            'author_id' => 'required|integer'
        ]);

        $articles = Article::on()->with(['inDocument'])
            ->whereHas('articleAuthor', function (Builder $builder) use ($validated) {
                $builder->where('users.id', $validated['author_id']);
            })
            ->whereBetween('created_at', [
                Carbon::parse($validated['date_from'])->startOfDay()->toDateTimeString(),
                Carbon::parse($validated['date_to'])->endOfDay()->toDateTimeString(),

            ])
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'result' => true,
            'html'   => view('Render.Report.AuthorReport.article_list', ['list' => $articles])->render(),
            'total'  => $articles->count()
        ]);
    }


    public function createDocument(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'article_ids' => 'required|array',
                'author_id'   => 'required|integer'
            ]);

            $author = User::on()->find($validated['author_id']);

            if (
                empty($author['fio_for_doc'])
                ||
                empty($author['inn_for_doc'])
                ||
                empty($author['contract_number_for_doc'])
                ||
                empty($author['date_contract_for_doc'])
            ) {
                return redirect()->back()->with(['error' => 'Необходимо внести данные в карточке автора для генерации документа.']);
            }

            $fio = $author['fio_for_doc'];
            $author['nameAndInitials'] = explode(' ', $fio)[0] . ' ' . mb_substr(explode(' ', $fio)[1], 0, 1) . '. ' . mb_substr(explode(' ', $fio)[2], 0, 1) . '.';

            $types = [
                'act' => 'АКТ',
                'tz'  => 'ТЗ'
            ];

            foreach ($types as $type => $typeName) {

                $articles = Article::on()
                    ->selectRaw("
                        id,
                        article,
                        without_space,
                        price_author,
                        CAST(((without_space / 1000) * price_author) as DECIMAL(10,2)) as price_article
                    ")
                    ->whereIn('id', $validated['article_ids'])
                    ->orderByDesc('id')
                    ->get();

                $amount['originAmount'] = number_format($articles->sum('price_article'), 2, '.', '');
                $amount['amount'] = (int)$amount['originAmount'];
                $amount['decimal'] = number_format(explode('.', $amount['originAmount'])[1], 0, '', '');

                [$fileName, $url] = $this->generateAndSavePDFFile($author, $validated['article_ids'], $amount, $type, $typeName);

                $attr = [
                    'author_id' => $author->id,
                    'url'       => $url,
                    'file_name' => $fileName,
                    'type'      => $typeName
                ];
                $documentReport = DocumentReport::on()->create($attr);
                $documentReport->sroccArticles()->attach($validated['article_ids']);
            }

            DB::commit();

            return redirect()->back()->with(['success' => 'Файл успешно создан. [file: ' . $fileName . ']']);

        } catch (\Exception $exception) {

            if (!empty($url)) {
                if (Storage::disk('public')->exists($url)) {
                    Storage::disk('public')->delete($url);
                }
            }
            DB::rollBack();

            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $document = DocumentReport::on()->find($id);
        if (Storage::disk('public')->exists($document->url)) {
            Storage::disk('public')->delete($document->url);
        }
        $fileName = $document->file_name;
        CrossDocumentReportArticle::on()->where('document_report_id', $id)->delete();
        $document->delete();

        return redirect()->back()->with(['success' => 'Файл успешно удален. [file: ' . $fileName . ']']);
    }


    public function sendFile(Request $request)
    {
        DB::beginTransaction();
        try {
            $document = DocumentReport::on()->find($request->document_id);

            $filePath = 'storage/' . $document->url;

            Mail::to($request->email_author)->send(new DocumentMail($filePath));

            $document->setAttribute('is_send', true);
            $document->setAttribute('date_time_send', now());
            $document->save();

            DB::commit();

            return redirect()->back()->with(['success' => 'Файл успешно отправлен. [file: ' . $document->file_name . ']']);

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $request
     * @return array
     */
    private function monthElseRange($request)
    {

        if (!empty($request->month)) {
            $startDate = Carbon::parse($request->month)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($request->month)->endOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($request->start_date ?? now()->startOfMonth())->format('Y-m-d');
            $endDate = Carbon::parse($request->end_date ?? now()->endOfMonth())->format('Y-m-d');
        }

        return [$startDate, $endDate];
    }

    /*
     * генерация и сохранение файла
     */
    private function generateAndSavePDFFile($author, $articles, $amount, $type, $typeName)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        // генерация pdf
        $dompdf = new Dompdf($options);

        $articles = Article::on()
            ->selectRaw("
                id,
                article,
                without_space,
                price_author,
                CAST(((without_space / 1000) * price_author) as DECIMAL(10,2)) as price_article
            ")
            ->whereIn('id', $articles)
            ->orderByDesc('id')
            ->get();

        $uniqueNumberDocument = Redis::get('unique_number_document');
        if (is_null($uniqueNumberDocument)) {
            Redis::set('unique_number_document', 1);
            $uniqueNumberDocument = 1;
        }

        $html = view('pdf.' . $type, [
            'articles'             => $articles,
            'author'               => $author,
            'amount'               => $amount,
            'currentDate'          => DocumentHelper::currentDateFormat(),
            'dateDocumentAuthor'   => DocumentHelper::currentDateFormat($author['date_contract_for_doc']),
            'uniqueNumberDocument' => $uniqueNumberDocument,
        ])->render();

        if ($type == 'act') {
            Redis::set('unique_number_document', ($uniqueNumberDocument + 1));
        }

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->loadHtml($html, 'UTF-8');

        $dompdf->render();

        $file = $dompdf->output();

        // параметры названия файла
        $path = 'report_author/' . $author->id . '/';
        $authorName = str_replace(' ', '_', $author->full_name);
        $currentDate = now()->format('d-m-Y');
        $instance = 0;
        $extension = '.pdf';

        // генерируем название файла и путь к нему
        $filename = $authorName . '_' . '(' . $typeName . ')' . '_' . $currentDate . ($instance == 0 ? '' : '(' . $instance . ')') . $extension;
        $url = $path . $filename;

        while (Storage::disk('public')->exists($url)) {
            $instance++;
            $filename = $authorName . '_' . '(' . $typeName . ')' . '_' . $currentDate . ($instance == 0 ? '' : '(' . $instance . ')') . $extension;
            $url = $path . $filename;
        }

        Storage::disk('public')->put($url, $file);

        return [$filename, $url];
    }
}

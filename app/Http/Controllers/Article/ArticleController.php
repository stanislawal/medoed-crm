<?php

namespace App\Http\Controllers\Article;

use App\Constants\NotificationTypeConstants;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Article;
use App\Models\Client\Client;
use App\Models\CrossArticleAuthor;
use App\Models\Currency;
use App\Models\Project\Cross\CrossprojectArticle;
use App\Models\Project\Cross\CrossProjectClient;
use App\Models\Project\Project;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\UserHelper;
use App\Models\CrossArticleRedactor;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $currency = Currency::on()->get()->toArray();
        $articles = Article::on()->selectRaw("
            articles.*,
            (COALESCE(articles.without_space, 0) * (COALESCE(articles.price_client, 0) / 1000)) as gross_income
        ")
            ->with([
                'articleProject.projectClients',
                'articleProject.projectAuthor',
                'articleCurrency', 'articleManager', 'articleAuthor', 'articleRedactor'
            ])->orderBy('id', 'desc');

        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get();

        $articles->when(UserHelper::isManager(), function ($where) {
            $where->where('manager_id', UserHelper::getUserId());
        });

        $this->filter($articles, $request);

        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        $list = $articles->get()->toArray();

        $statistics = $this->calculate($list, $request);

        $articles = $articles->paginate(50);

        $project = Project::on()->select(['id', 'project_name'])
            ->with(['projectAuthor', 'projectClients'])
            ->get()->toArray();

        return view('article.list_article', [
            'articles' => $articles,
            'currency' => $currency,
            'projects' => $project,
            'managers' => $managers,
            'statistics' => $statistics,
            'authors' => $authors,
        ]);
    }

    private function calculate($articles, $request)
    {
        $list = collect($articles);

        [$dateStart, $dateEnd] = $this->getDate($request);

        $countDays = $dateStart->diff($dateEnd)->days + 1;

        if (($dateStart < now()) && ($dateEnd > now())) {
            $currentDay = $dateStart->diff(now())->days + 1;
            $expectation = (int)($list->sum('without_space') / $currentDay * $countDays);
            $passed = $list->filter(function ($item) {

                return Carbon::parse($item['created_at'])->format('Y-m-d') == now()->format('Y-m-d');

            })->sum('without_space') ?? 0 / $currentDay;
        }

        $result = [
            "count_days_in_range" => $countDays,
            "current_day_in_range" => $currentDay ?? "Текущий день не входит в диапазон",
            "expectation" => $expectation ?? "Невозможно вычислить",
            "passed" => $passed ?? 0,
            "sum_gross_income" => $list->sum('gross_income'),
            "sum_without_space" => $list->sum('without_space')
        ];

        $salary = UserHelper::getUser()->manager_salary ?? 0;

        if (!is_null($request->manager_id)) {
            $salary = User::on()->find($request->manager_id)->manager_salary ?? 0;
        }

        if (UserHelper::isManager() || !is_null($request->manager_id)) {
            $result["manager_salary"] = $result['passed'] / 1000 * $salary;
        }

        return $result;
    }

    public function create()
    {
        $currency = Currency::on()->get()->toArray();
        $project = Project::on()->get()->toArray();
        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get()->toArray();
        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();
        return view('article.article_create', [
            'currency' => $currency,
            'project' => $project,
            'managers' => $managers,
            'authors' => $authors,
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $attr = $request->only(['article', 'manager_id', 'without_space', 'id_currency', 'link_text', 'project_id', 'price_client', 'price_author', 'price_redactor', 'manager_salary']);
            $article_id = Article::on()->create($attr)->id;

            if ($request->has('author_id') && count($request->author_id) > 0) {
                foreach ($request->author_id as $author) {
                    $authors[] = [
                        'article_id' => $article_id,
                        'user_id' => $author,
                    ];
                }
                CrossArticleAuthor::on()->insert($authors);
            }

            if ($request->has('redactor_id') && count($request->redactor_id) > 0) {
                foreach ($request->redactor_id as $redactor) {
                    $redactors[] = [
                        'article_id' => $article_id,
                        'user_id' => $redactor,
                    ];
                }
                CrossArticleRedactor::on()->insert($redactors);
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Статья успешно создана']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $oldArticle = Article::on()->select(['without_space', 'price_client', 'price_author'])->find($id)->toArray();

        $attr = $request->only([
            'article',
            'manager_id',
            'without_space',
            'id_currency',
            'gross_income',
            'link_text',
            'check',
            'project_id',
            'price_author',
            'price_redactor',
            'price_client',
            'payment_amount',
            'payment_date'
        ]);

        Article::on()->where('id', $id)->update($attr);

        if ($request->has('authors_id')) {

            CrossArticleAuthor::on()->where('article_id', $id)->delete();

            if (!is_null($request->authors_id ?? null)) {
                CrossArticleAuthor::on()->insert([
                    'article_id' => $id,
                    'user_id' => $request->authors_id,
                ]);
            }
        }

        if ($request->has('redactors_id')) {

            CrossArticleRedactor::on()->where('article_id', $id)->delete();

            if (count($request->redactors_id) > 0) {
                foreach ($request->redactors_id as $redactor) {
                    $rows[] = [
                        'article_id' => $id,
                        'user_id' => $redactor,
                    ];
                }

                CrossArticleRedactor::on()->insert($rows);
            }
        }

        $newArticle = Article::on()->find($id)->toArray();

        // проверяет измененные даныне и создает уведомление при условии
        $this->whereNotify($id, $oldArticle, $newArticle);

        return response()->json(['success' => 'Статья успешно обновлена']);
    }

    public function destroy($id)
    {
        Article::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Статья успешно удалена']);
    }

    private function filter(&$articles, $request)
    {
        $articles->when(!empty($request->article), function ($where) use ($request) {
            $where->where('article', $request->article);
        });

        $articles->when(!empty($request->manager_id), function ($where) use ($request) {
            $where->where('manager_id', $request->manager_id);
        });
        $articles->whereBetween('created_at', $this->getDate($request));

        $articles->when(!empty($request->date_article), function ($where) use ($request) {
            $where->whereRaw("DATE(created_at) = '{$request->date_article}'");
        });

        $articles->when(!empty($request->project_id), function ($where) use ($request) {
            $where->wherehas('articleProject', function ($where) use ($request) {
                $where->where('id', $request->project_id);
            });
        });

        $articles->when(!empty($request->author_id), function ($where) use ($request) {
            $where->wherehas('articleAuthor', function ($where) use ($request) {
                $where->whereIn('users.id', $request->author_id);
            });
        });

    }

    private function getDate($request)
    {
        if ($request->has('date_from')) {
            $startDate = Carbon::parse($request->date_from)->startOfDay();
        } else {
            $startDate = Carbon::parse(now())->startOfMonth();
        }

        if ($request->has('date_before')) {
            $endDate = Carbon::parse($request->date_before)->startOfDay();
        } else {
            $endDate = Carbon::parse(now())->endOfMonth();
        }

        return [
            $startDate,
            $endDate
        ];
    }

    private function whereNotify($id, $oldArticle, $newArticle)
    {
        $change = "";

        if($oldArticle['without_space'] != $newArticle['without_space']){
            $change = $change . 'ЗБП: <strong>' . $oldArticle['without_space'] . "/" . $newArticle['without_space'] . '</strong><br> ';
        }

        if($oldArticle['price_client'] != $newArticle['price_client']){
            $change = $change . 'Цена заказчика: <strong>' . $oldArticle['price_client'] . "/" . $newArticle['price_client'] . '</strong><br> ';
        }

        if($oldArticle['price_author'] != $newArticle['price_author']){
            $change = $change . 'Цена автора: <strong>' . $oldArticle['price_author'] . "/" . $newArticle['price_author'] . '</strong><br> ';
        }

        if($change != ''){
            (new NotificationController())->createNotification(
                NotificationTypeConstants::CHANGE_ARTICLE,
                '',
                $id,
                $change
            );
        }
    }
}

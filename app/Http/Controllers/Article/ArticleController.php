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

        $articles->where('ignore', false);

        $this->filter($articles, $request);

        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        $statistics = $this->calculate($articles, $request);

        $articles = $articles->paginate(20);

        $project = Project::on()->select(['id', 'project_name'])
            ->with(['projectAuthor', 'projectClients'])
            ->get()->toArray();

        $redactors = User::on()
            ->select(['id', 'full_name'])
            ->whereIn('id', CrossArticleRedactor::on()->selectRaw("DISTINCT user_id as id")->get()
                ->pluck('id') ?? [])
            ->get()
            ->toArray();


        return view('article.list_article', [
            'articles' => $articles,
            'currency' => $currency,
            'projects' => $project,
            'managers' => $managers,
            'statistics' => $statistics,
            'authors' => $authors,
            'redactors' => $redactors,
        ]);
    }

    private function calculate(Builder $builder, $request)
    {
        $result = Article::on()->selectRaw("
            sum(articles.without_space) as sum_without_space,
            sum(articles.gross_income) as sum_gross_income,
            sum(
                if(cast(articles.created_at as date) = '" . now()->format('Y-m-d') . "', articles.without_space, 0)
            ) as passed
        ")->fromSub($builder, 'articles')
            ->first();

        [$dateStart, $dateEnd] = $this->getDate($request);

        $countDays = $this->diffInWeekdays($dateStart, $dateEnd);

        if (($dateStart < now()) && ($dateEnd > now())) {
            $currentDay = $this->diffInWeekdays($dateStart, now());

            $expectation = $result['sum_without_space'] / $currentDay * $countDays;
            $passed = $result['passed'];
        }

        $indicators = [
            "count_days_in_range" => $countDays,
            "current_day_in_range" => $currentDay ?? 0,
            "expectation" => $expectation ?? 0,
            "passed" => $passed ?? 0,
            "sum_gross_income" => $result['sum_gross_income'],
            "sum_without_space" => $result['sum_without_space']
        ];

        $salary = UserHelper::getUser()->manager_salary ?? 0;

        if (!is_null($request->manager_id)) {
            $salary = User::on()->find($request->manager_id)->manager_salary ?? 0;
        }

        if (UserHelper::isManager() || !is_null($request->manager_id)) {
            $indicators["manager_salary"] = ((int)$result['sum_without_space'] ?? 0) / 1000 * $salary;
        }

        return $indicators;
    }

    private function diffInWeekdays($startDate, $endDate)
    {
        return $startDate->diffInDaysFiltered(function (Carbon $date) {
            return $date->isWeekday();
        }, $endDate);
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
            'project'  => $project,
            'managers' => $managers,
            'authors'  => $authors,
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
                        'user_id'    => $author,
                    ];
                }
                CrossArticleAuthor::on()->insert($authors);
            }

            if ($request->has('redactor_id') && count($request->redactor_id) > 0) {
                foreach ($request->redactor_id as $redactor) {
                    $redactors[] = [
                        'article_id' => $article_id,
                        'user_id'    => $redactor,
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
            'payment_date',
            'redactor_payment_amount',
            'redactor_payment_date'
        ]);

        Article::on()->where('id', $id)->update($attr);

        if ($request->has('authors_id')) {

            CrossArticleAuthor::on()->where('article_id', $id)->delete();

            if (!is_null($request->authors_id ?? null)) {
                CrossArticleAuthor::on()->insert([
                    'article_id' => $id,
                    'user_id'    => $request->authors_id,
                ]);
            }
        }

        if ($request->has('redactors_id')) {

            CrossArticleRedactor::on()->where('article_id', $id)->delete();

            if (count($request->redactors_id) > 0) {
                foreach ($request->redactors_id as $redactor) {
                    $rows[] = [
                        'article_id' => $id,
                        'user_id'    => $redactor,
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

    // Обновляет статус игнора статьи у автора
    public function changeIgnoreArticle(Request $request, $id)
    {
        $ignore = $request->ignore ?? false;
        Article::on()->where('id', $id)->update(['ignore' => $ignore]);
        return redirect()->back();
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

        $articles->when(!empty($request->redactor_id), function ($where) use ($request) {
            $where->wherehas('articleRedactor', function ($where) use ($request) {
                $where->whereIn('users.id', $request->redactor_id);
            });
        });

    }

    private function getDate($request)
    {
        if ($request->has('date_from')) {
            $startDate = Carbon::parse($request->date_from)->startOfDay();
        } else {
            $startDate = Carbon::parse(now())->startOfMonth()->startOfDay();
        }

        if ($request->has('date_before')) {
            $endDate = Carbon::parse($request->date_before)->endOfDay();
        } else {
            $endDate = Carbon::parse(now())->endOfMonth()->endOfDay();
        }

        return [
            $startDate,
            $endDate
        ];
    }

    private function whereNotify($id, $oldArticle, $newArticle)
    {
        $change = "";

        if ($oldArticle['without_space'] != $newArticle['without_space']) {
            $change = $change . 'ЗБП: <strong>' . $oldArticle['without_space'] . "/" . $newArticle['without_space'] . '</strong><br> ';
        }

        if ($oldArticle['price_client'] != $newArticle['price_client']) {
            $change = $change . 'Цена заказчика: <strong>' . $oldArticle['price_client'] . "/" . $newArticle['price_client'] . '</strong><br> ';
        }

        if ($oldArticle['price_author'] != $newArticle['price_author']) {
            $change = $change . 'Цена автора: <strong>' . $oldArticle['price_author'] . "/" . $newArticle['price_author'] . '</strong><br> ';
        }

        if ($change != '') {
            (new NotificationController())->createNotification(
                NotificationTypeConstants::CHANGE_ARTICLE,
                '',
                $id,
                $change
            );
        }
    }
}

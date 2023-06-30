<?php

namespace App\Http\Controllers\Project;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Client\Client;
use App\Models\Currency;
use App\Models\Project\Cross\CrossProjectAuthor;
use App\Models\Project\Cross\CrossProjectClient;
use App\Models\Project\Mood;
use App\Models\Project\Project;
use App\Models\Project\Style;
use App\Models\Project\Theme;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    //Для отображения (вывода) всех записей
    public function index(Request $request)
    {
        $clients = Client::on()->get()->toArray(); //Достаем всех клиентов (заказчиков)
        $themes = Theme::on()->get()->toArray(); //Достаем все темы проектов
        $moods = Mood::on()->get()->toArray(); //достаем все настроения из бд
        $statuses = Status::on()->get()->toArray(); //Достаем все статусы из бд
        $style = Style::on()->get()->toArray();
        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get();
        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();


        $projects = Project::on()
            ->selectRaw("
                projects.*,
                statuses.name as project_status,
                themes.name,
                users.full_name
            ")
            ->with([
                'projectTheme',
                'projectUser',
                'projectStatus',
                'projectClients.socialNetwork',
                'projectAuthor',
                'projectStyle'
            ]);

        $projects->leftJoin('users', 'users.id', '=', 'projects.manager_id');

        $projects->leftJoin('statuses', 'statuses.id', '=', 'projects.status_id');
        $projects->leftJoin('themes', 'themes.id', '=', 'projects.theme_id');

        $projects->when(UserHelper::isManager(), function ($where) {
            $where->where('manager_id', UserHelper::getUserId());
        });

        // фильтр
        $this->filter($projects, $request);

        $projects->orderBy('id', 'desc');
        $projects = $projects->get()->toArray();

        return view('project.list_projects', [
            'projects' => $projects,
            'statuses' => $statuses,
            'moods' => $moods,
            'themes' => $themes,
            'clients' => $clients,
            'style' => $style,
            'managers' => $managers,
            'authors' => $authors,
        ]);

    }

    //Страница формы создания. Возвращаем view на которой форма создания
    public function create()
    {
        $clients = Client::on()->get()->toArray(); //Достаем всех клиентов (заказчиков)
        $themes = Theme::on()->get()->toArray(); //Достаем все темы проектов
        $moods = Mood::on()->get()->toArray(); //достаем все настроения из бд
        $statuses = Status::on()->get()->toArray(); //Достаем все статусы из бд
        $style = Style::on()->get()->toArray();
        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get();
        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        //передаем данные в view
        return view('project.projects_create', [
            'statuses' => $statuses,
            'moods' => $moods,
            'themes' => $themes,
            'clients' => $clients,
            'style' => $style,
            'managers' => $managers,
            'authors' => $authors,
        ]);
    }

    //Сюда приходят данные из формы и записывает в базу
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $attr = [
                'manager_id' => $request->manager_id ?? null,
                'theme_id' => $request->theme_id ?? null,
                'total_symbols' => $request->total_symbols ?? null,
                'price_per' => $request->price_per ?? null,
                'project_name' => $request->project_name ?? null,
                'mood_id' => $request->mood_id ?? null,
                'status_id' => $request->status_id ?? null,
                'pay_info' => $request->pay_info ?? null,
                'price_author' => $request->price_author ?? null,
                'price_client' => $request->price_author ?? null,
                'start_date_project' => $request->start_date_project ?? null,
                'contract' => $request->contract ?? null,
                'contract_exist' => $request->contract_exist ?? null,
                'comment' => $request->comment ?? null,
                'business_area' => $request->business_area ?? null,
                'link_site' => $request->link_site ?? null,
                'invoice_for_payment' => $request->invoice_for_payment ?? null,
                'project_perspective' => $request->project_perspective ?? null,
                'payment_terms' => $request->payment_terms ?? null,
                'style_id' => $request->style_id ?? null,
                'type_task' => $request->type_task ?? null,
                'dop_info' => $request->dop_info ?? null,
//                'characteristic' => $request->characteristic ?? null,
                'created_user_id' => UserHelper::getUserId()
            ];

            $project_id = Project::on()->create($attr)->id;

            if ($request->has('client_id') && count($request->client_id) > 0) {
                $clients = [];

                foreach ($request->client_id as $client) {
                    $clients[] = [
                        'project_id' => $project_id,
                        'client_id' => $client
                    ];
                }

                CrossProjectClient::on()->insert($clients);
            }

            DB::commit();

            return redirect()->route('project.index')->with(['success' => 'Новый проект успешно создан.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Произошла ошибка при создании проекта.']);
        }
    }

    //Вывести информацию об одной записи
    public function show($project)
    {

    }

    //Страница редактирования одной записи
    public function edit($project)
    {

        $clients = Client::on()->get()->toArray(); //Достаем всех клиентов (заказчиков)
        $themes = Theme::on()->get()->toArray(); //Достаем все темы проектов
        $moods = Mood::on()->get()->toArray(); //достаем все настроения из бд
        $statuses = Status::on()->get()->toArray(); //Достаем все статусы из бд
        $style = Style::on()->get()->toArray();
//        $articles = Article::on()->get()->toArray(); //Достаем все записи и данные о них из бд
        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get();
//        $currency = Currency::on()->get()->toArray();
        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        $projectInfo = Project::on()
            ->with(['projectTheme', 'projectAuthor', 'projectUser', 'projectStatus', 'projectClients'])
            ->find($project)
            ->toArray();
        return view('project.project_edit', [
            'projectInfo' => $projectInfo,
            'statuses' => $statuses,
            'moods' => $moods,
            'themes' => $themes,
            'clients' => $clients,
            'style' => $style,
            'managers' => $managers,
            'authors' => $authors,
        ]);
    }

    //Обновляет запись (в бд)
    public function update(Request $request, $project)
    {
        $attr = [
            'manager_id' => $request->manager_id ?? null,
            'theme_id' => $request->theme_id ?? null,
            'total_symbols' => $request->total_symbols ?? null,
            'price_per' => $request->price_per ?? null,
            'project_name' => $request->project_name ?? null,
            'mood_id' => $request->mood_id ?? null,
            'status_id' => $request->status_id ?? null,
            'pay_info' => $request->pay_info ?? null,
            'price_author' => $request->price_author ?? null,
            'price_client' => $request->price_author ?? null,
            'start_date_project' => $request->start_date_project ?? null,
            'contract' => $request->contract ?? null,
            'contract_exist' => $request->contract_exist ?? null,
            'comment' => $request->comment ?? null,
            'business_area' => $request->business_area ?? null,
            'link_site' => $request->link_site ?? null,
            'invoice_for_payment' => $request->invoice_for_payment ?? null,
            'project_perspective' => $request->project_perspective ?? null,
            'payment_terms' => $request->payment_terms ?? null,
            'style_id' => $request->style_id ?? null,
            'type_task' => $request->type_task ?? null,
            'dop_info' => $request->dop_info ?? null,
            'created_user_id' => UserHelper::getUserId()
        ];

        Project::on()->where('id', $project)->update($attr);

        $this->updateAuthorForProject($project, $request->author_id ?? []);
        $this->updateClientsForProject($project, $request->client_id ?? []);

        return redirect()->back()->with(['success' => 'Данные успешно обновлены.']);
    }

    //Удалить запись
    public function destroy($id)
    {
        Project::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Проект успешно удален']);
    }


    private function updateAuthorForProject(int $projectId, array $authorsId)
    {
        CrossProjectAuthor::on()->where('project_id', $projectId)->delete();

        $authors = [];

        foreach ($authorsId as $authorId) {
            $authors[] = [
                'user_id' => $authorId,
                'project_id' => $projectId
            ];
        }

        if (count($authors) > 0) {
            CrossProjectAuthor::on()->insert($authors);
        }
    }

    private function updateClientsForProject(int $projectId, array $clientsId)
    {
        CrossProjectClient::on()->where('project_id', $projectId)->delete();

        $clients = [];

        foreach ($clientsId as $clientId) {
            $clients[] = [
                'project_id' => $projectId,
                'client_id' => $clientId
            ];
        }

        if (count($clients) > 0) {
            CrossProjectClient::on()->insert($clients);
        }
    }

    public function partialUpdate($id, Request $request)
    {
        try {
            $param = $request->only(['status_id', 'comment', 'date_last_change', 'check', 'status_payment_id']);

            if (count($param) > 0) {
                Project::on()->where('id', $id)->update($param);
            }

            return response()->json(['result' => true]);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Фильтр
     *
     * @param $projects
     * @param $request
     * @return void
     */
    private function filter(&$projects, $request)
    {
        $projects->when(!empty($request->id), function ($where) use ($request) {
            $where->where('projects.id', $request->id);
        });

        $projects->when(!empty($request->created_at), function ($where) use ($request) {
            $where->whereRaw("DATE(created_at) = '" . $request->created_at . "'");
        });

        $projects->when(!empty($request->manager_id), function ($where) use ($request) {
            $where->where('manager_id', $request->manager_id);
        });

        $projects->when(!empty($request->project_name), function ($where) use ($request) {
            $where->where('project_name', 'like', '%' . $request->project_name . '%');
        });

        $projects->when(!empty($request->price_per), function ($where) use ($request) {
            $where->where('price_per', '>=', $request->price_per);
        });

        $projects->when(!empty($request->contract), function ($where) use ($request) {
            $where->where('contract', $request->contract);
        });

        $projects->when(!empty($request->status_id), function ($where) use ($request) {
            $where->whereIn('status_id', $request->status_id);
        });

        $projects->when(!empty($request->theme_id), function ($where) use ($request) {
            $where->where('theme_id', $request->theme_id);
        });

        $projects->when((!empty($request->date_from) && (!empty($request->date_before))), function ($where) use ($request) {
            $dateStart = Carbon::parse($request->date_from)->startOfDay();
            $dateEnd = Carbon::parse($request->date_before)->endOfDay();
            $where->whereBetween('created_at', [$dateStart, $dateEnd]);
        });

        // sort
        if (str_contains($request->sort, '|')) {
            $parts = explode('|', $request->sort);

            $orderBy = implode('.', $parts);

            $projects->orderByRaw($orderBy . ' ' . $request->direction ?? 'asc');

        } else {
            $projects->when(!empty($request->sort), function ($orderBy) use ($request) { // use ($request) - это то самое замыкание, о котормо я тебе говорил)))
                $orderBy->orderBy($request->sort, $request->direction ?? 'asc');
            });
        }
    }
}

<?php

namespace App\Http\Controllers\Project;

use App\Constants\NotificationTypeConstants;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Article;
use App\Models\Client\Client;
use App\Models\Client\SocialNetwork;
use App\Models\Client\SourceClient;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\Project\Cross\CrossProjectAuthor;
use App\Models\Project\Cross\CrossProjectClient;
use App\Models\Project\Mood;
use App\Models\Project\NotifiProject;
use App\Models\Project\Project;
use App\Models\Project\Style;
use App\Models\Project\Theme;
use App\Models\Requisite;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{

    private AutoCreateEvent $autoCreateEvent;

    public function __construct(AutoCreateEvent $autoCreateEvent)
    {
        $this->autoCreateEvent = $autoCreateEvent;
    }

    // Для отображения (вывода) всех записей
    public function index(Request $request)
    {
        $clients = Client::on()->get()->toArray(); //Достаем всех клиентов (заказчиков)
        $themes = Theme::on()->get()->toArray(); //Достаем все темы проектов
        $statuses = Status::on()->get()->toArray(); //Достаем все статусы из бд
        $style = Style::on()->get()->toArray();
        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->where('is_work', true)
            ->get();
        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        $socialNetworks = SocialNetwork::on()->get();

        $projects = Project::on()
            ->selectRaw("
                projects.*,
                statuses.name as project_status,
                themes.name,
                users.full_name,
                moods.name as mood_name,
                moods.color as mood_color,
                CAST(projects.price_client as FLOAT) as price_client_float,
                SUM(
                    CASE
                        WHEN articles.is_fixed_price_client = 1
                        THEN articles.price_client
                        ELSE (articles.price_client * (articles.without_space / 1000))
                    END
                ) as sum_gross_income
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
        $projects->leftJoin('styles', 'styles.id', '=', 'projects.style_id');
        $projects->leftJoin('statuses', 'statuses.id', '=', 'projects.status_id');
        $projects->leftJoin('themes', 'themes.id', '=', 'projects.theme_id');
        $projects->leftJoin('moods', 'moods.id', '=', 'projects.mood_id');

        $projects->leftJoin('articles', function ($leftJoin) {
            $leftJoin->on('articles.project_id', '=', 'projects.id')
                ->whereBetween('articles.created_at', [
                    Carbon::parse(now())->startOfMonth()->toDateTimeString(),
                    Carbon::parse(now())->endOfMonth()->toDateTimeString()
                ])
                ->where('articles.ignore', false);
        });

        $projects->when(UserHelper::isManager(), function ($where) {
            $where->where('projects.manager_id', UserHelper::getUserId());
        });

        // фильтр
        $this->filter($projects, $request);

        $projects->groupBy('projects.id');
        $projects->orderBy('id', 'desc');
        $projects = $projects->paginate(50);


        return view('project.list_projects', [
            'projects'       => $projects,
            'statuses'       => $statuses,
            'themes'         => $themes,
            'clients'        => $clients,
            'style'          => $style,
            'managers'       => $managers,
            'authors'        => $authors,
            'socialNetworks' => $socialNetworks
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
        })
            ->where('is_work', true)
            ->get();
        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();
        $requisite = Requisite::on()->get();

        //передаем данные в view
        return view('project.projects_create', [
            'statuses'  => $statuses,
            'moods'     => $moods,
            'themes'    => $themes,
            'clients'   => $clients,
            'style'     => $style,
            'managers'  => $managers,
            'authors'   => $authors,
            'requisite' => $requisite,
        ]);
    }

    //Сюда приходят данные из формы и записывает в базу
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $attr = [
                'manager_id'                       => $request->manager_id ?? null,
                'theme_id'                         => $request->theme_id ?? null,
                'total_symbols'                    => $request->total_symbols ?? null,
                'project_name'                     => $request->project_name ?? null,
                'mood_id'                          => $request->mood_id ?? null,
                'status_id'                        => $request->status_id ?? null,
                'pay_info'                         => $request->pay_info ?? null,
                'price_author'                     => $request->price_author ?? null,
                'price_client'                     => $request->price_client ?? null,
                'start_date_project'               => $request->start_date_project ?? null,
                'contract'                         => $request->contract ?? null,
                'nds'                              => $request->nds ?? null,
                'contract_exist'                   => $request->contract_exist ?? null,
                'comment'                          => $request->comment ?? null,
                'business_area'                    => $request->business_area ?? null,
                'link_site'                        => $request->link_site ?? null,
                'invoice_for_payment'              => $request->invoice_for_payment ?? null,
                'project_perspective'              => $request->project_perspective ?? null,
                'payment_terms'                    => $request->payment_terms ?? null,
                'style_id'                         => $request->style_id ?? null,
                'type_task'                        => $request->type_task ?? null,
                'dop_info'                         => $request->dop_info ?? null,
                'created_user_id'                  => UserHelper::getUserId(),
                'project_team'                     => $request->project_team ?? null,
                'product_company'                  => $request->product_company ?? null,
                'link_to_resources'                => $request->link_to_resources ?? null,
                'mass_media_with_publications'     => $request->mass_media_with_publications ?? null,
                'task_client'                      => $request->task_client ?? null,
                'content_public_platform'          => $request->content_public_platform ?? null,
                'project_perspective_sees_account' => $request->project_perspective_sees_account ?? null,
                'edo'                              => $request->edo ?? null,
                'project_status_text'              => $request->project_status_text ?? null,
                'date_notification'                => $request->date_notification ?? null,
                'date_last_change'                 => $request->date_last_change ?? null,
                'call_up'                          => $request->call_up ?? null,
                'date_connect_with_client'         => $request->date_connect_with_client ?? null,
                'company_name'                     => $request->company_name ?? null,
                'deadline_accepting_work'          => $request->deadline_accepting_work ?? null,
                'contract_number'                  => $request->contract_number ?? null,
                'legal_name_company'               => $request->legal_name_company ?? null,
                'period_work_performed'            => $request->period_work_performed ?? null,
                'requisite_id'                     => $request->requisite_id ?? null,
            ];

            $project = Project::on()->create($attr);

            $project_id = $project->id;

            $this->updateNotifiProject($project_id, $request);

            if ($request->has('client_id') && count($request->client_id) > 0) {

                // внести данные в проект, взятые у климмента, если стоят галочки
                $this->setClientInfoFotProject($request, $project_id);

                $clients = [];

                foreach ($request->client_id as $client) {
                    $clients[] = [
                        'project_id' => $project_id,
                        'client_id'  => $client
                    ];
                }

                CrossProjectClient::on()->insert($clients);
            }

            if ($request->has('author_id') && count($request->author_id) > 0) {
                $authors = [];

                foreach ($request->author_id as $author) {
                    $authors[] = [
                        'project_id' => $project_id,
                        'user_id'    => $author
                    ];
                }

                CrossProjectAuthor::on()->insert($authors);
            }

            if ($request->manager_id != null && !in_array($project->status_id, [2, 3, 5])) { // статус не "Ожидается ТЗ", "Стоп", "Ушел"
                (new NotificationController())->createNotification(
                    NotificationTypeConstants::ASSIGNED_PROJECT,
                    $request->manager_id,
                    $project_id);
            }

            DB::commit();

            return redirect()->route('project.index')->with(['success' => 'Новый проект успешно создан.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => "Произошла ошибка при создании проекта. {$e->getMessage()}"]);
        }
    }

    /**
     * @param $request
     * @param $project_id
     * @return void
     */
    private function setClientInfoFotProject($request, $project_id)
    {
        $clientID = collect($request->client_id)->first();
        $client = Client::on()->find($clientID);

        $projectInfoClient = [];

        switch (true) {
            case $request->has('link_site_parse') :
                $projectInfoClient['link_site'] = $client->site;

            case $request->has('business_area_parse') :
                $projectInfoClient['business_area'] = $client->scope_work;

            case $request->has('company_name_parse') :
                $projectInfoClient['company_name'] = $client->company_name;
        }

        Project::on()->where('id', $project_id)->update($projectInfoClient);
    }

    // Страница редактирования одной записи
    public function edit($project)
    {
        $clients = Client::on()->get()->toArray(); //Достаем всех клиентов (заказчиков)
        $themes = Theme::on()->get()->toArray(); //Достаем все темы проектов
        $moods = Mood::on()->get()->toArray(); //достаем все настроения из бд
        $statuses = Status::on()->get()->toArray(); //Достаем все статусы из бд
        $style = Style::on()->get()->toArray();
        $sourceClients = SourceClient::on()->get();
        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->where('is_work', true)
            ->get();
        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        $requisite = Requisite::on()->get();

        $projectInfo = Project::on()
            ->with([
                'projectTheme',
                'projectAuthor',
                'projectUser',
                'projectStatus',
                'projectClients.socialNetwork',
                'projectClients.files',
                'projectEvent' => function ($builder) {
                    $builder->orderBy('id', 'desc');
                },
                'files'
            ])
            ->find($project)
            ->toArray();

        $socialNetwork = SocialNetwork::on()->orderBy('id')
            ->get()
            ->toArray();

        $projectInfo['project_clients'] = collect($projectInfo['project_clients'])->map(function ($item) {
            $data = collect($item['social_network'])->map(function ($item) {
                return [
                    'socialnetrowk_id' => $item['id'],
                    'link'             => $item['pivot']['description'],
                    'view'             => $item['pivot']['view'],
                ];
            })->toArray();
            $item['json'] = json_encode($data);
            return $item;
        })->toArray();

        $notifiProject = NotifiProject::on()->where('project_id', $project)->get()->pluck('day')->toArray() ?? [];

        $projectClient = $projectInfo['project_clients'][0] ?? null;

        return view('project.project_edit', [
            'projectInfo'   => $projectInfo,
            'statuses'      => $statuses,
            'moods'         => $moods,
            'themes'        => $themes,
            'clients'       => $clients,
            'style'         => $style,
            'managers'      => $managers,
            'authors'       => $authors,
            'socialNetwork' => $socialNetwork,
            'notifiProject' => $notifiProject,
            'projectClient' => $projectClient,
            'requisite'     => $requisite,
            'sourceClients' => $sourceClients
        ]);
    }

    //Обновляет запись (в бд)
    public function update(Request $request, $project)
    {

        $this->autoCreateEvent->createEvent($project, $request->all());

        $oldProject = Project::on()->find($project);

        $attr = [
            'manager_id'                       => $request->manager_id ?? null,
            'theme_id'                         => $request->theme_id ?? null,
            'total_symbols'                    => $request->total_symbols ?? null,
            'project_name'                     => $request->project_name ?? null,
            'mood_id'                          => $request->mood_id ?? null,
            'status_id'                        => $request->status_id ?? null,
            'pay_info'                         => $request->pay_info ?? null,
            'price_author'                     => $request->price_author ?? null,
            'price_client'                     => $request->price_client ?? null,
            'start_date_project'               => $request->start_date_project ?? null,
            'date_notification'                => $request->date_notification ?? null,
            'contract'                         => $request->contract ?? null,
            'nds'                              => $request->nds ?? null,
            'contract_exist'                   => $request->contract_exist ?? null,
            'comment'                          => $request->comment ?? null,
            'business_area'                    => $request->business_area ?? null,
            'link_site'                        => $request->link_site ?? null,
            'invoice_for_payment'              => $request->invoice_for_payment ?? null,
            'project_perspective'              => $request->project_perspective ?? null,
            'payment_terms'                    => $request->payment_terms ?? null,
            'style_id'                         => $request->style_id ?? null,
            'type_task'                        => $request->type_task ?? null,
            'dop_info'                         => $request->dop_info ?? null,
            'date_last_change'                 => $request->date_last_change ?? null,
            'created_user_id'                  => UserHelper::getUserId(),
            'project_team'                     => $request->project_team ?? null,
            'product_company'                  => $request->product_company ?? null,
            'link_to_resources'                => $request->link_to_resources ?? null,
            'mass_media_with_publications'     => $request->mass_media_with_publications ?? null,
            'task_client'                      => $request->task_client ?? null,
            'content_public_platform'          => $request->content_public_platform ?? null,
            'project_perspective_sees_account' => $request->project_perspective_sees_account ?? null,
            'edo'                              => $request->edo ?? null,
            'call_up'                          => $request->call_up ?? null,
            'date_connect_with_client'         => $request->date_connect_with_client ?? null,
            'company_name'                     => $request->company_name ?? null,
            'deadline_accepting_work'          => $request->deadline_accepting_work ?? null,
            'contract_number'                  => $request->contract_number ?? null,
            'legal_name_company'               => $request->legal_name_company ?? null,
            'period_work_performed'            => $request->period_work_performed ?? null,
            'requisite_id'                     => $request->requisite_id ?? null,
        ];

        if (UserHelper::isAdmin()) {
            $attr['project_status_text'] = $request->project_status_text ?? null;
        }

        Project::on()->where('id', $project)->update($attr);

        $this->updateAuthorForProject($project, $request->author_id ?? []);
        $this->updateClientsForProject($project, $request->client_id ?? []);
        $this->updateNotifiProject($project, $request);

        $newProject = Project::on()->find($project);

        if ($newProject['manager_id'] != $oldProject['manager_id'] && !in_array($newProject->status_id, [2, 3, 5])) {  // статус не "Ожидается ТЗ", "Стоп", "Ушел"
            (new NotificationController())->createNotification(
                NotificationTypeConstants::ASSIGNED_PROJECT,
                $request->manager_id,
                $project
            );
        }

        if ($newProject['price_client'] != $oldProject['price_client'] && !in_array($newProject->status_id, [2, 3, 5])) { // статус не "Ожидается ТЗ", "Стоп", "Ушел"
            (new NotificationController())->createNotification(
                NotificationTypeConstants::CHANGE_PRICE_PROJECT,
                '',
                $project
            );
        }

        return redirect()->back()->with(['success' => 'Данные успешно обновлены.']);
    }

    //Удалить запись
    public function destroy($id)
    {
        $project = Project::on()->with([
            'projectArticle',
            'payment',
            'files'
        ])->find($id);

        $relation = [];

        if (count($project->projectArticle) > 0) {
            $relation[] = 'статьями';
        }

        if (count($project->payment) > 0) {
            $relation[] = 'оплатами';
        }

        if (count($project->files) > 0) {
            $relation[] = 'файлами';
        }

        if (count($relation) > 0) {
            return redirect()->back()->with(['error' => 'Невозможно удалить проект (id ' . $id . '). Есть связь c ' . implode(', ', $relation)]);
        }

        Notification::on()->where('project_id', $id)->delete();

        Project::on()->find($id)->delete();

        return redirect()->back()->with(['success' => 'Проект успешно удален']);
    }


    private function updateAuthorForProject(int $projectId, array $authorsId)
    {
        CrossProjectAuthor::on()->where('project_id', $projectId)->delete();

        $authors = [];

        foreach ($authorsId as $authorId) {
            $authors[] = [
                'user_id'    => $authorId,
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
                'client_id'  => $clientId
            ];
        }

        if (count($clients) > 0) {
            CrossProjectClient::on()->insert($clients);
        }
    }

    private function updateNotifiProject($projectId, $request)
    {
        if (!is_null($request->days) || !is_null($request->weekday)) {

            NotifiProject::on()->where('project_id', $projectId)->delete();

            $list = array_merge($request->days ?? [], $request->weekday ?? []);

            if (count($list) > 0) {
                $data = [];
                foreach ($list as $item) {
                    $data[] = [
                        'project_id' => $projectId,
                        'day'        => $item
                    ];
                }

                NotifiProject::on()->insert($data);
            }
        }
    }

    public function partialUpdate($id, Request $request)
    {
        $param = $request->only([
            'status_id',
            'comment',
            'date_last_change',
            'check',
            'status_payment_id',
            'duty',
            'date_notification',
            'project_status_text',
            'mood_id',
            'payment_terms',
            'date_connect_with_client'
        ]);

        if (count($param) > 0) {
            Project::on()->where('id', $id)->update($param);
        }

        if ($request->ajax()) {
            return response()->json(['result' => true]);
        }

        return redirect()->back();
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
        if (count($request->all()) == 0) {
            $this->setFilter($request);
        }

        $projects->when(!empty($request->social_network_id), function (Builder $where) use ($request) {
            $where->whereHas('projectClients.socialNetwork', function ($where) use ($request) {
                $where->where('social_networks.id', $request->social_network_id);
            });
        });

        $projects->when(!empty($request->except_status_id), function ($where) use ($request) {
            $where->whereNotIn('projects.status_id', $request->except_status_id);
        });

        $projects->when(!empty($request->id), function ($where) use ($request) {
            $where->where('projects.id', $request->id);
        });

        $projects->when(!empty($request->created_at), function ($where) use ($request) {
            $where->whereRaw("DATE(projects.created_at) = '" . $request->created_at . "'");
        });

        $projects->when(!empty($request->manager_id), function ($where) use ($request) {
            $where->where('projects.manager_id', $request->manager_id);
        });

        $projects->when(!empty($request->author_id), function ($where) use ($request) {
            $where->whereHas('projectAuthor', function ($where) use ($request) {
                $where->where('users.id', $request->author_id);
            });
        });

        $projects->when(!empty($request->project_name), function ($where) use ($request) {
            $where->where('project_name', 'like', '%' . $request->project_name . '%');
        });

        $projects->when(!empty($request->price_client_float), function ($where) use ($request) {
            $where->whereRaw("CAST(price_client as FLOAT) >= {$request->price_client_float} ");
        });

        $projects->when(!empty($request->price_author), function ($where) use ($request) {
            $where->where('price_author', $request->price_author);
        });

        $projects->when(!empty($request->contract), function ($where) use ($request) {
            $where->where('contract', $request->contract);
        });

        $projects->when(!empty($request->nds), function ($where) use ($request) {
            $where->where('nds', $request->nds);
        });

        $projects->when(!empty($request->status_id), function ($where) use ($request) {
            $where->whereIn('status_id', $request->status_id);
        });

        $projects->when(!empty($request->theme_id), function ($where) use ($request) {
            $where->where('theme_id', $request->theme_id);
        });

        $projects->when(!empty($request->style_id), function ($where) use ($request) {
            $where->where('style_id', $request->style_id);
        });

        $projects->when((!empty($request->date_from) && (!empty($request->date_before))), function ($where) use ($request) {
            $dateStart = Carbon::parse($request->date_from)->startOfDay();
            $dateEnd = Carbon::parse($request->date_before)->endOfDay();
            $where->whereRaw("projects.created_at between '{$dateStart}' and '{$dateEnd}'");
        });

        $projects->when(!empty($request->mood_id), function ($where) use ($request) {
            $where->where('projects.mood_id', $request->mood_id == 'empty' ? null : $request->mood_id);
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

        $this->saveFilterHistory($request);
    }

    private function saveFilterHistory($request)
    {
        if (empty($request->reset_filters)) {
            $history = collect($request->all())->except(['sort', 'direction', '_token'])->toArray();
            $history = json_encode($history);
            Cookie::queue('project_filter', $history);
        }
    }

    /**
     * Save param for request
     *
     * @param $request
     * @return void
     */
    private function setFilter(&$request)
    {
        $key = "project_filter";
        $filterHistory = json_decode(Cookie::get($key) ?? '', TRUE) ?? [];
        foreach ($filterHistory as $key => $value) {
            $request->request->set($key, $value);
        }
    }

    public function deleteCheckbox()
    {
        Project::on()->where('manager_id', UserHelper::getUserId())->update(['check' => 0]);
        return redirect()->back();
    }
}



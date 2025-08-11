@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')

    @include('Window.EventProject.create', ['project_id' => $projectInfo['id']])

    <div class="container">

        <div class="col-12 mb-3">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        <h1 class="mb-3 text-center">Форма редактирования проекта</h1>
        <div class="accordion mb-3" id="socialNetworkLink">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button p-2 text-12 collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#clients" aria-expanded="false">
                        <strong>Заказчик</strong>
                    </button>
                </h2>
                <div id="clients" class="accordion-collapse collapse" style="">
                    <div class="accordion-body">
                        <form class="d-none"></form>

                        @if(!is_null($projectClient))
                            <form action="{{ route('client.update', ['client' => $projectClient['id']]) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="mb-3 col-12 col-lg-6 mt-2">
                                        <label for="" class="form-label">Контактное лицо / должность</label>
                                        <input type="text" value="{{$projectClient['name']}}"
                                               class="form-control form-control-sm"
                                               name="name">
                                    </div>

                                    <div class="mb-3 col-12 col-lg-6">
                                        <label for="" class="form-label">Сфера деятельности компании</label>
                                        <textarea id="characteristic" rows="2" name="scope_work"
                                                  class="form-control form-control-sm">{{ $projectClient['scope_work'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-12 mb-3 col-lg-6">
                                        <label for="" class="form-label">ЛПР / контакты</label>
                                        <input type="text" class="form-control form-control-sm" name="lpr_contacts"
                                               value="{{ $projectClient['lpr_contacts'] ?? '' }}">
                                    </div>

                                    <div class="mb-3 col-12 col-lg-6">
                                        <label for="" class="form-label">Дополнительные контакты</label>
                                        <input type="text" value="{{$projectClient['contact_info']}}"
                                               class="form-control form-control-sm"
                                               name="contact_info">
                                    </div>

                                    <div class="mb-3 col-12 col-lg-6">
                                        <label for="" class="form-label">Название компании заказчика</label>
                                        <input type="text" value="{{$projectClient['company_name']}}"
                                               class="form-control form-control-sm"
                                               name="company_name">
                                    </div>
                                    <div class="mb-3 col-12 col-lg-6">
                                        <label for="" class="form-label">Сайт компании</label>
                                        <input type="text" value="{{$projectClient['site']}}"
                                               class="form-control form-control-sm"
                                               name="site">
                                    </div>

                                    <div class="col-12 mb-3 col-lg-6">
                                        <label for="" class="form-label">Информация о работе команды</label>
                                        <textarea id="characteristic" rows="2" name="info_work_team"
                                                  class="form-control form-control-sm">{{ $projectClient['info_work_team'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-12 mb-3 col-lg-6">
                                        <label for="" class="form-label">Дополнительная информация</label>
                                        <textarea id="characteristic" rows="2" name="additional_info"
                                                  class="form-control form-control-sm">{{ $projectClient['additional_info'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-12 mb-3 col-lg-6">
                                        <label for="source_client_id" class="form-label">Источник поступления</label>
                                        <select name="source_client_id" id="source_client_id"
                                                class="form-select form-select-sm">
                                            <option value="">Не выбрано</option>
                                            @foreach($sourceClients as $item)
                                                <option value="{{ $item['id'] }}"
                                                        @if($item['id'] == $projectClient['source_client_id']) selected @endif>{{ $item['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 col-12">
                                        <label for="characteristic" class="form-label">Портрет и общая хар-ка</label>
                                        <textarea id="characteristic" name="characteristic"
                                                  class="form-control">{{$projectClient['characteristic']}}</textarea>
                                    </div>

                                    <div class="mb-3 col-12">
                                        <div class="section_socialwork p-3 border shadow">
                                            <div class="mb-2">
                                                <label class="form-label">Место ведения диалога</label>
                                                <div class="btn btn-sm btn-primary py-0 px-1 add">Добавить</div>
                                                <input type="hidden" data-id="{{ $projectClient['id'] }}"
                                                       name="socialnetwork_info"
                                                       value="{{ $projectClient['json'] }}" class="socialnetwork_info">
                                            </div>
                                            <div class="items_socialwork" data-id="{{ $projectClient['id'] }}">
                                                @foreach($projectClient['social_network'] as $socialNetworkClientItem)
                                                    <div class="input-group input-group-sm mb-3 item">
                                                        <input class="form-check-input m-0" type="checkbox"
                                                               style="margin: 8px 12px 0 0!important;"
                                                               name="view"
                                                               @if($socialNetworkClientItem['pivot']['view']) checked @endif
                                                               onclick="window.write_socialnetwork(this)"
                                                        >
                                                        <select class="form-select form-select-sm" required
                                                                onchange="window.write_socialnetwork(this)">
                                                            <option value="">Не выбрано</option>
                                                            @foreach ($socialNetwork as $item)
                                                                <option
                                                                    @if($socialNetworkClientItem['id'] == $item['id']) selected
                                                                    @endif value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="input-group-text"><i
                                                                class="fas fa-arrows-alt-h"></i></span>
                                                        <input placeholder="Ник" class="form-control form-control-sm"
                                                               type="text"
                                                               name="description"
                                                               value="{{ $socialNetworkClientItem['pivot']['description'] }}"
                                                               required oninput="window.write_socialnetwork(this)">
                                                        <div class="btn btn-sm btn-danger delete"
                                                             onclick="window.write_socialnetwork(this)">Удалить
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-sm btn-success">Сохранить</button>
                                    </div>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>

            <div class="accordion-item file_client">
                <h2 class="accordion-header">
                    <button class="accordion-button p-2 text-12 collapsed fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#files_client" aria-expanded="true"
                            aria-controls="collapseOne">
                        Документы заказчика ({{ count($projectClient['files'] ?? []) }})
                    </button>
                </h2>
                <div id="files_client" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">

                        @if(!is_null($projectClient))

                            <div class="text-center text-16">
                                Добавить новый документ
                            </div>
                            <div class="row">
                            </div>
                            <div class="my-3">
                                <div class="mb-2">
                                    <input type="file" name="file" class="form-control form-control-sm">
                                </div>
                                <div class="mb-2">
                                    <input type="text" class="form-control form-control-sm" name="comment_file"
                                           placeholder="Комментарий к файлу">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <div class="btn btn-sm btn-primary"
                                         onclick="saveFile('client_id', {{ $projectClient['id'] }}, '{{ route('project_file.upload') }}')">
                                        Загрузить
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom my-3"></div>
                            <div class="container__files">
                                @include('Render.Project.file_list', ['column' => 'client_id', 'files' => $projectClient['files'], 'id' => $projectClient['id']])
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button p-2 text-12 collapsed fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#events" aria-expanded="true"
                            aria-controls="collapseOne">
                        Хронология проекта ({{ count($projectInfo['project_event'] ) }})
                    </button>
                </h2>
                <div id="events" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_event">
                            Добавить событие
                        </div>
                        <div class="table-responsive">
                            <table class="table table-cut" id="basic-datatables">
                                <thead>
                                <tr>
                                    <th style="width: 75px">Дата</th>
                                    <th>Событие</th>
                                    @role('Администратор')
                                    <th style="width: 70px">Действие</th>@endrole
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($projectInfo['project_event'] as $item)
                                    <tr>
                                        <td class="nowrap">{{ $item['date'] }}</td>
                                        <td>
                                            <div style="white-space: pre-line;">{!! $item['comment'] !!}</div>
                                        </td>
                                        @role('Администратор')
                                        <td>
                                            <form
                                                action="{{ route('project-event.destroy', ['project_event' => $item['id']]) }}"
                                                method="post">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-sm btn-danger">Удалить</button>
                                            </form>
                                        </td>
                                        @endrole
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">нет событий</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item file_project">
                <h2 class="accordion-header">
                    <button class="accordion-button p-2 text-12 collapsed fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#files" aria-expanded="true"
                            aria-controls="collapseOne">
                        Документы проекта ({{ count($projectInfo['files']) }})
                    </button>
                </h2>
                <div id="files" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="text-center text-16">
                            Добавить новый документ
                        </div>
                        <div class="row">
                        </div>
                        <div class="my-3">
                            <div class="mb-2">
                                <input type="file" name="file" class="form-control form-control-sm">
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" name="comment_file"
                                       placeholder="Комментарий к файлу">
                            </div>
                            <div class="d-flex justify-content-end">
                                <div class="btn btn-sm btn-primary"
                                     onclick="saveFile('project_id' ,{{ $projectInfo['id'] }}, '{{ route('project_file.upload') }}')">
                                    Загрузить
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom my-3"></div>
                        <div class="container__files">
                            @include('Render.Project.file_list', ['column' => 'project_id', 'files' => $projectInfo['files'], 'id' => $projectInfo['id']])
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <form action="{{route('project.update', ['project' => $projectInfo['id']])}}" method="POST"
              data-form-name="edit__project" class="mb-5 p-3 border shadow bg-white">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-end mb-3">
                <div class="btn btn-danger btn-sm mr-3 w-auto" style="display: none;" data-role="cancel"
                     onclick="onEdit('edit__project', true)">Отмена
                </div>
                <button class="btn btn-success btn-sm mr-3 w-auto" style="display: none;">Обновить</button>
                <div class="btn btn-primary btn-sm w-auto" data-role="edit"
                     onclick="onEdit('edit__project', false)">Редактировать
                </div>
            </div>

            <div class="text-18 font-weight-bold mb-3 text-center" style="background-color: #f1c232">
                Информация о проекте
            </div>

            <hr class="bg-black">

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Название проекта</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="project_name" required disabled
                           value="{{ $projectInfo['project_name'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Название компании (Бренда)</label>
                <div class="col-sm-9">
                    <input type="text" disabled class="form-control form-control-sm" name="company_name"
                           value="{{ $projectInfo['company_name'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Дата поступления проекта</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control form-control-sm" name="start_date_project"
                           disabled value="{{ $projectInfo['start_date_project'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Менеджер</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-2" name="manager_id" disabled>
                        <option value="">Не выбрано</option>
                        @foreach ($managers as $manager)
                            <option @if($manager['id'] == $projectInfo['manager_id']) selected
                                    @endif value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Автор в проекте</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm select-2" multiple name="author_id[]"
                            disabled>
                        @foreach ($authors as $author)
                            <option
                                @if(in_array($author['id'], collect($projectInfo['project_author'])->pluck('id')->toArray()))
                                    selected
                                @endif value="{{$author['id']}}">{{$author['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Команда проекта</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="project_team"
                           value="{{ $projectInfo['project_team'] ?? '' }}" disabled>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Классификация</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="theme_id" disabled
                            value="{{ $projectInfo['theme_id'] ?? '' }}">
                        @foreach ($themes as $theme)
                            <option value="{{$theme['id']}}"
                                    @if($theme['id'] == $projectInfo['theme_id'])
                                        selected
                                @endif
                            >{{$theme['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Приоритетность</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" title="Пожалуйста, выберите"
                            name="style_id"
                            disabled>
                        @foreach ($style as $item)
                            <option value="{{$item['id']}}"
                                    @if($item['id'] == $projectInfo['style_id']) selected @endif
                            >{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Ссылка на сайт</label>
                <div class="col-sm-9">
                    <input disabled type="text" value="{{ $projectInfo['link_site'] }}"
                           class="form-control form-control-sm" name="link_site">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Ссылка на ресурсы компании (соцсети, каналы)</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="link_to_resources" disabled
                           value="{{ $projectInfo['link_to_resources'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">СМИ в которых были публикации/ссылки</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm"
                           value="{{ $projectInfo['mass_media_with_publications'] ?? '' }}" disabled
                           name="mass_media_with_publications">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Площадка размещения нашего контента</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="content_public_platform" disabled
                           value="{{ $projectInfo['content_public_platform'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Сфера бизнеса</label>
                <div class="col-sm-9">
                    <textarea type="text" disabled
                              class="form-control form-control-sm"
                              name="business_area">{{ $projectInfo['business_area'] }}</textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Продукт, который продает компания</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="product_company" disabled
                           value="{{ $projectInfo['product_company'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Задача заказчика</label>
                <div class="col-sm-9">
                    <textarea disabled class="form-control form-control-sm" style="resize: vertical!important;"
                              name="task_client">{{ $projectInfo['task_client'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Задача проекта</label>
                <div class="col-sm-9">
                    <textarea disabled class="form-control form-control-sm" style="resize: vertical!important;"
                              name="type_task">{{ $projectInfo['type_task'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Перспектива данная клиентом</label>
                <div class="col-sm-9">
                    <textarea disabled type="text"
                              class="form-control form-control-sm"
                              name="project_perspective">{{ $projectInfo['project_perspective'] }}</textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Перспектива проекта (как ее видит аккаунт)</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="2" class="form-control form-control-sm" disabled
                              name="project_perspective_sees_account">{{ $projectInfo['project_perspective_sees_account'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Доп. информация о проекте</label>
                <div class="col-sm-9">
                <textarea type="text" disabled rows="2" style="resize: both;" class="form-control form-control-sm"
                          name="dop_info">{{ $projectInfo['dop_info'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Состояние проекта</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" name="status_id" required disabled>
                        @foreach ($statuses as $status)
                            <option value="{{$status['id']}}"
                                    @if($status['id'] == $projectInfo['status_id'])
                                        selected
                                @endif
                            >{{$status['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Текущие задачи</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="2" class="form-control form-control-sm" name="comment"
                              placeholder="Комментарий"
                              disabled>{{ $projectInfo['comment'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Напоминание</label>
                <div class="col-sm-9">
                    <input type="date" disabled
                           class="form-control form-control-sm" name="date_connect_with_client"
                           value="{{$projectInfo['date_connect_with_client']}}">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">План на месяц</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="2"
                              class="form-control form-control-sm @if(!\App\Helpers\UserHelper::isAdmin()) block @endif "
                              name="project_status_text"
                              placeholder="Укажите комментарий к проекту"
                              disabled>{{ $projectInfo['project_status_text'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Созвон</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="call_up" disabled
                           value="{{ $projectInfo['call_up'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Дата последнего контакта</label>
                <div class="col-sm-9">
                    <input disabled type="date" value="{{$projectInfo['date_last_change']}}"
                           class="form-control form-control-sm" name="date_last_change">
                </div>
            </div>

            <hr class="bg-black">

            <div class="text-18 font-weight-bold mb-3 text-center" style="background-color: #f1c232">
                Для спецпроектов
            </div>

            <hr class="bg-black">


            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Тема проекта </label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" type="text" name="project_theme_service" value="{{ $projectInfo['project_theme_service'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Отчетная дата</label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" type="date" name="reporting_data" value="{{ $projectInfo['reporting_data'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Условия оплаты</label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" type="text" name="terms_payment" value="{{ $projectInfo['terms_payment'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Регион продвижения</label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" type="text" name="region" value="{{ $projectInfo['region'] ?? '' }}">
                </div>
            </div>


            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Ссылка на план работы</label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" type="text" name="passport_to_work_plan" value="{{ $projectInfo['passport_to_work_plan'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Часы</label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" type="number" name="hours" value="{{ $projectInfo['hours'] + 0 }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Общая сумма договора</label>
                <div class="col-sm-9">
                    <input disabled class="form-control form-control-sm" type="number" name="total_amount_agreement" value="{{ $projectInfo['total_amount_agreement'] + 0 }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Ведущий специалист</label>
                <div class="col-sm-9">
                    <select disabled class="form-select form-select-sm select2-with-color" name="leading_specialist_id">
                        <option value="">Не выбрано</option>
                        @foreach($specialists as $item)
                            <option @if($projectInfo['leading_specialist_id'] == $item->id) selected @endif data-color="{{ $item->color }}" value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="bg-black">

            <div class="text-18 font-weight-bold mb-3 text-center" style="background-color: #f1c232">
                Условия оплаты
            </div>

            <hr class="bg-black">


            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Цена заказчика</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm" disabled
                               value="{{ $projectInfo['price_client'] ?? '' }}" type="text"
                               name="price_client">
                        <div class="input-group-append input-group-sm">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Цена автора</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm" step="0.01" type="number"
                               name="price_author"
                               disabled value="{{ $projectInfo['price_author'] ?? '' }}">
                        <div class="input-group-append input-group-sm">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Условия оплаты</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="pay_info"
                           disabled value="{{ $projectInfo['pay_info'] ?? '' }}">
                </div>
            </div>


            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Сроки оплаты</label>
                <div class="col-sm-9">
                    <input disabled type="text" value="{{$projectInfo['payment_terms']}}"
                           class="form-control form-control-sm" name="payment_terms">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Дата оплаты</label>
                <div class="col-sm-9">
                    <input disabled type="date" value="{{$projectInfo['date_notification']}}"
                           class="form-control form-control-sm" name="date_notification">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Дни оплаты</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <div class="w-50">
                            <select class="form-control form-control-sm input-group select-2" multiple name="days[]"
                                    disabled>
                                <option value="">Не выбрано</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option @if(in_array($i, $notifiProject ?? [])) selected
                                            @endif  value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="w-50">
                            <select class="form-control form-control-sm select-2" multiple name="weekday[]" disabled>
                                <option value="">Не выбрано</option>
                                @foreach(\App\Helpers\DateHelper::getWeekdayList() as $key => $value)
                                    <option @if(in_array((string)$key, $notifiProject)) selected
                                            @endif  value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Счёт для оплаты</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="requisite_id" disabled>
                        <option value="">Не выбрано</option>
                        @foreach($requisite as $item)
                            <option value="{{ $item['id'] }}"
                                    @if($item['id'] == $projectInfo['requisite_id']) selected @endif>{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Срок принятия работы (проверки текста)</label>
                <div class="col-sm-9">
                    <input type="text" disabled class="form-control form-control-sm" name="deadline_accepting_work"
                           value="{{ $projectInfo['deadline_accepting_work'] ?? '' }}">
                </div>
            </div>

            <hr class="bg-black">

            <div class="text-18 font-weight-bold mb-3 text-center" style="background-color: #f1c232">
                Договор, ЭДО, NDA
            </div>

            <hr class="bg-black">

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Заказчики</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm select-2" multiple
                            title="Пожалуйста, выберите"
                            name="client_id[]" disabled>
                        <option value="">Не выбрано</option>
                        @foreach ($clients as $client)
                            <option value="{{$client['id']}}"
                                    @if(in_array($client['id'], collect($projectInfo['project_clients'])->pluck('id')->toArray()))
                                        selected
                                @endif
                            >{{$client['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Договор</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-contract" name="contract"
                            disabled>
                        <option value="1" @if($projectInfo['contract'] == true) selected @endif>Да
                        </option>
                        <option value="0" @if($projectInfo['contract'] == false) selected @endif>Нет
                        </option>
                    </select>

                    <input type="text"
                           @if(!(boolean)$projectInfo['contract']) style="display: none;" @endif
                           class="form-control input-contract mt-2 form-control-sm"
                           placeholder="Вставьте ссылку на договор"
                           disabled
                           value="{{$projectInfo['contract_exist']}}" name="contract_exist">

                    <input type="text"
                           @if(!(boolean)$projectInfo['contract']) style="display: none;" @endif
                           class="form-control input-contract mt-2 form-control-sm"
                           placeholder="Номер договора"
                           disabled
                           value="{{$projectInfo['contract_number'] ?? ''}}" name="contract_number">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Подпись NDA</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-contract" name="nds"
                            disabled>
                        <option value="1" @if($projectInfo['nds'] == true) selected @endif>Да
                        </option>
                        <option value="0" @if($projectInfo['nds'] == false) selected @endif>Нет
                        </option>
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Юридическое название компании</label>
                <div class="col-sm-9">
                    <input type="text" disabled class="form-control form-control-sm" name="legal_name_company"
                           value="{{ $projectInfo['legal_name_company'] ?? '' }}">
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-3 col-form-label">Настроение</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" name="mood_id" disabled
                            value="{{ $projectInfo['mood_id'] ?? '' }}">
                        @foreach ($moods as $mood)
                            <option @if($mood['id'] == $projectInfo['mood_id']) selected
                                    @endif value="{{$mood['id']}}">{{$mood['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">ЭДО</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="edo" disabled>
                        <option value="1" @if($projectInfo['edo'] == 1) selected @endif>
                            Да
                        </option>
                        <option @if($projectInfo['edo'] == 0) selected @endif value="0">
                            Нет
                        </option>
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Срок подписания акта выполненных работ</label>
                <div class="col-sm-9">
                    <input type="text" disabled class="form-control form-control-sm" name="period_work_performed"
                           value="{{ $projectInfo['period_work_performed'] ?? '' }}">
                </div>
            </div>

            <dic class="d-flex justify-content-end">
                <div class="btn btn-danger btn-sm mr-3 w-auto" style="display: none;" data-role="cancel"
                     onclick="onEdit('edit__project', true)">Отмена
                </div>
                <button class="btn btn-success btn-sm mr-3 w-auto" style="display: none;">Обновить</button>
                <div class="btn btn-primary btn-sm w-auto" data-role="edit"
                     onclick="onEdit('edit__project', false)">Редактировать
                </div>
            </dic>
        </form>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script src="{{asset('js/files.js')}}?v=@version"></script>

    <script>
        $('.select-contract').change(function () {
            if ($(this).val() === '0') {
                $('.input-contract').hide();

            } else {
                $('.input-contract').show();
            }
        });

        $('.section_socialwork').on('click', '.delete', function () {
            const id = $(this).parent('.item').parent('.items_socialwork').attr('data-id');
            $(this).parent('div').remove();
            window.save(id);
        })

        $('.section_socialwork .add').click(function () {
            const itemsSocialwork = $(this).parent('div').next('.items_socialwork');

            $.ajax({
                url: '{{ route("socialnetwork.get_select") }}',
                method: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            }).done((res) => {
                itemsSocialwork.append(res.html);
            })
        });

        window.write_socialnetwork = function (el) {
            const id = $(el).parent('.item').parent('.items_socialwork').attr('data-id');
            window.save(id);
        }

        window.save = function (id) {
            var array = [];

            $('.items_socialwork[data-id="' + id + '"] .item').each(function (i, item) {
                array.push({
                    'socialnetrowk_id': $(this).children('select').val(),
                    'link': $(this).children('input[name="description"]').val(),
                    'view': $(this).children('input[name="view"]').is(':checked')
                })
            });

            $('.socialnetwork_info[data-id="' + id + '"]').val(JSON.stringify(array));
        };
    </script>
@endsection

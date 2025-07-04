@extends('layout.markup')

@section('title')
    База проектов
@endsection

@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="row p-0s">
        <div class="col-12">
            {{--        ФИЛЬТР--}}
            <div class="col-12 mb-3">
                @include('Answer.custom_response')
                @include('Answer.validator_response')
                <div class="w-100 shadow border rounded p-3 bg-white">
                    <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                            class="fa fa-search search-icon mr-2"></i>Поиск
                    </div>
                    <form action="{{ route('project.index') }}" method="GET" class="check__field">
                        <div class="row m-0" id="search" style="display: none;">
                            <div class="w-100 row m-0 py-3">
                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">ID</label>
                                    <input type="text" class="form-control form-control-sm" name="id"
                                           value="{{ request()->id ?? '' }}">
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">По дате:</label>
                                    <input type="date" class="form-control form-control-sm" name="created_at"
                                           value="{{ request()->created_at ?? '' }}">
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Автор</label>
                                    <select class="form-select form-select-sm" name="author_id">
                                        <option value="">Не выбрано</option>
                                        @foreach ($authors as $author)
                                            <option
                                                @if($author['id'] == request()->author_id)
                                                    selected
                                                @endif
                                                value="{{$author['id']}}">{{$author['full_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if(\App\Helpers\UserHelper::isManager())
                                    <div class="form-group col-12 col-md-4 col-lg-3">
                                        <label class="form-label">Менеджер</label>
                                        <select class="form-select form-select-sm" disabled>
                                            <option>{{ auth()->user()->full_name }}</option>
                                        </select>
                                    </div>
                                @else
                                    <div class="form-group col-12 col-md-4 col-lg-3">
                                        <label for="" class="form-label">Менеджер</label>
                                        <select class="form-select form-select-sm" name="manager_id">
                                            <option value="">Не выбрано</option>
                                            @foreach ($managers as $manager)
                                                <option
                                                    @if($manager['id'] == request()->manager_id)
                                                        selected
                                                    @endif
                                                    value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Название проекта</label>
                                    <input type="text" class="form-control form-control-sm" name="project_name"
                                           value="{{ request()->project_name ?? '' }}">
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Цена за 1000 (от)</label>
                                    <input type="text" class="form-control form-control-sm" name="price_client_float"
                                           value="{{ request()->price_client_float ?? '' }}">
                                </div>


                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Цена автора</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="number" step="0.01" min="0.01"
                                               name="price_author"
                                               value="{{ request()->price_author ?? '' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Договор</label>
                                    <select class="form-select form-select-sm" name="contract">
                                        <option @if(request()->contract == null) selected @endif value="">Не выбрано
                                        </option>
                                        <option @if(request()->contract == '1') selected @endif value="1">Да</option>
                                        <option @if(request()->contract == '0') selected @endif value="0">Нет</option>
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">NDA</label>
                                    <select class="form-select form-select-sm" name="nds">
                                        <option @if(request()->nds == null) selected @endif value="">Не выбрано</option>
                                        <option @if(request()->nds == '1') selected @endif value="1">Да</option>
                                        <option @if(request()->nds == '0') selected @endif value="0">Нет</option>
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Состояние</label>
                                    <select class="form-control select-2" multiple
                                            name="status_id[]" id="">
                                        <option value="">Не выбрано</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{$status['id']}}"
                                                    @if(in_array($status['id'], request()->status_id ?? []))
                                                        selected
                                                @endif
                                            >{{$status['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Исключение состояний</label>
                                    <select class="form-control select-2" multiple
                                            name="except_status_id[]" id="">
                                        <option value="">Не выбрано</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{$status['id']}}"
                                                    @if(in_array($status['id'], request()->except_status_id ?? []))
                                                        selected
                                                @endif
                                            >{{$status['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Тема</label>
                                    <select class="form-select form-select-sm" name="theme_id" id="">
                                        <option value="">Не выбрано</option>
                                        @foreach ($themes as $theme)
                                            <option value="{{$theme['id']}}"
                                                    @if($theme['id'] == request()->theme_id)
                                                        selected
                                                @endif
                                            >{{$theme['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Приоритетность</label>
                                    <select class="form-select form-select-sm" name="style_id" id="">
                                        <option value="">Не выбрано</option>
                                        @foreach ($style as $item)
                                            <option value="{{$item['id']}}"
                                                    @if($item['id'] == request()->style_id)
                                                        selected
                                                @endif
                                            >{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label class="form-label">Диапазон добавления</label>
                                    <div class="input-group">
                                        <input type="date" name="date_from" class="form-control form-control-sm"
                                               value="{{ request()->date_from ?? "" }}">
                                        <input type="date" name="date_before" class="form-control form-control-sm"
                                               value="{{ request()->date_before ?? "" }}">
                                    </div>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Место ведения диалога</label>
                                    <select class="form-select form-select-sm" name="social_network_id" id="">
                                        <option value="">Не выбрано</option>
                                        @foreach ($socialNetworks as $socialNetwork)
                                            <option value="{{$socialNetwork['id']}}"
                                                    @if($socialNetwork['id'] == request()->social_network_id)
                                                        selected
                                                @endif
                                            >{{$socialNetwork['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Юр. имя проекта</label>
                                    <input type="text" class="form-control form-control-sm" name="legal_name_company"
                                           value="{{ request()->legal_name_company ?? '' }}">
                                </div>

                                <div class="col-12 p-0">
                                    <div class="form-group col-12">
                                        <div class="w-100 d-flex justify-content-end">
                                            @if(count(request()->all()) > 0 && empty(request()->reset_filters))
                                                <a href="{{ route('project.index', ['reset_filters' => '1']) }}"
                                                   class="btn btn-sm btn-danger mr-3">Сбросить все</a>
                                            @endif
                                            <button class="btn btn-sm btn-success">Искать</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Администрирование проектов</h4>
                            <div class="text-16">Найдено записей: {{ $projects->total() }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="w-100 d-flex justify-content-center mb-3">
                            {{ $projects->appends(request()->input())->links('vendor.pagination.custom')  }}
                        </div>
                        <div class="table-responsive">
                            <table id="basic-datatables"
                                   class="display table table-hover table-head-bg-info table-center table-cut">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>
                                        <a href="{{ route('project.delete_checkboxes') }}" type="submit"
                                           class="text-white ">
                                            ✖
                                        </a>
                                    </th>
                                    <th></th>
                                    <th style="max-width: 80px;"
                                        class="sort-p">@include('components.table.sort', ['title' => 'Приор', 'column' => 'styles|name', 'routeName' => 'project.index'] )</th>
                                    <th class="sort-p">@include('components.table.sort', ['title' => 'Проект', 'column' => 'project_name', 'routeName' => 'project.index'] )</th>
                                    <th>Юр. имя проекта</th>
                                    <th>Контакт с клиентом</th>
                                    <th>Дата последнего контакта</th>
                                    <th>Дата оплаты</th>
                                    <th>Дата связи с клиентом</th>
                                    <th class="sort-p">@include('components.table.sort', ['title' => 'Состояние', 'column' => 'statuses|name', 'routeName' => 'project.index'] )</th>
                                    <th style="min-width: 200px !important;">Состояние проекта</th>
                                    <th style="min-width: 220px !important;">Перспектива проекта</th>
                                    @role('Администратор')
                                    <th class="sort-p">@include('components.table.sort', ['title' => 'ВД', 'column' => 'sum_gross_income', 'routeName' => 'project.index'] )</th>
                                    @endrole
                                    <th>План ВД</th>
                                    <th>Автор</th>
                                    <th class="sort-p">@include('components.table.sort', ['title' => 'Цена заказчика', 'column' => 'price_client_float', 'routeName' => 'project.index'] )</th>
                                    <th class="sort-p">@include('components.table.sort', ['title' => 'Цена автора', 'column' => 'price_author', 'routeName' => 'project.index'] )</th>
                                    <th>Маржа</th>
                                    <th class="sort-p">@include('components.table.sort', ['title' => 'Дог', 'column' => 'contract', 'routeName' => 'project.index'] )</th>
                                    <th>Место ведения диалога</th>

                                    <th class="sort-p">@include('components.table.sort', ['title' => 'Тема', 'column' => 'themes|name', 'routeName' => 'project.index'] )</th>
                                    <th class="sort-p">@include('components.table.sort', ['title' => 'NDA', 'column' => 'nds', 'routeName' => 'project.index'] )</th>
                                    @role('Администратор')
                                    <th>Дата поступления</th>
                                    <th class="sort-p">@include('components.table.sort', ['title' => 'Менеджер', 'column' => 'users|full_name', 'routeName' => 'project.index'] )</th>
                                    <th>Удалить</th>
                                    @endrole
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $project['id'] }}</td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            <input type="checkbox" name="check" @if((bool)$project['check']) checked
                                                   @endif
                                                   onchange="editCheckProject(this, '{{ route('project.partial_update', ['id'=> $project['id']]) }}')">
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            <a href="{{route('project.edit',['project'=> $project['id']])}}"><i
                                                    class="fas fa-grip-horizontal"></i></a>
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">{{$project['projectStyle']['name'] ?? '------'}}</td>
                                        <td style="padding: 0 10px 0 12px!important"><a
                                                href="{{ route('client_project.show', ['project' => $project['id'], 'month' => request()->month ?? now()->format('Y-m')]) }}">{{$project['project_name'] ?? '------'}}</a>
                                        </td>
                                        <td>
                                            {{ $project['legal_name_company'] }}
                                        </td>
                                        <td>
                                            @foreach($project['projectClients'][0]['socialNetwork'] as $item)
                                                @if($item['pivot']['view'])
                                                    <span
                                                        class="badge bg-primary">{{ $item['name'] }}:{{ $item['pivot']['description'] }}</span>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <div>
                                                <input class="form-control form-control-sm" style="max-width: 72px;"
                                                       onchange="editDateLastChangeProject(this, '{{ route('project.partial_update', ['id'=>$project['id']]) }}')"
                                                       name="date_last_change" type="date"
                                                       value="{{$project['date_last_change']}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <input class="form-control form-control-sm" style="max-width: 72px;"
                                                       onchange="editDatePayment(this, '{{ route('project.partial_update', ['id'=>$project['id']]) }}')"
                                                       name="date_notification" type="date"
                                                       value="{{$project['date_notification']}}">
                                            </div>
                                        </td>

                                        <td>
                                            <div>
                                                <input class="form-control form-control-sm" style="max-width: 72px;"
                                                       onchange="editDatePayment(this, '{{ route('project.partial_update', ['id' => $project['id']]) }}')"
                                                       name="date_connect_with_client" type="date"
                                                       value="{{$project['date_connect_with_client']}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <select
                                                    style="background-color: {{ $project['projectStatus']['color'] ?? "" }}; width: 120px;"
                                                    class="form-select form-select-sm mr-1" id="edit_status_project"
                                                    onchange="editStatusProject(this, '{{ route('project.partial_update', ['id'=> $project['id']]) }}')">
                                                    @foreach ($statuses as $status)
                                                        <option value="{{$status['id']}}"
                                                                @if($status['id'] == $project['status_id']) selected @endif
                                                        >{{$status['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            <div class="d-flex align-items-center">
                                                <textarea style="width: 100%; height: 100%; border: none"
                                                          onchange="editCommentProject(this, '{{ route('project.partial_update', ['id'=>$project['id']]) }}')"
                                                          type="text"
                                                >{{$project['comment']}}</textarea>
                                            </div>
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            @if(\App\Helpers\UserHelper::isAdmin())
                                                <div class="d-flex align-items-center">
                                                    <textarea style="width: 100%; height: 100%; border: none"
                                                              onchange="editStatusTextProject(this, '{{ route('project.partial_update', ['id'=>$project['id']]) }}')"
                                                              type="text"
                                                    >{{$project['project_status_text']}}</textarea>
                                                </div>
                                            @else
                                                {{ $project['project_status_text'] }}
                                            @endif
                                        </td>
                                        @role('Администратор')
                                        <td class="nowrap">{{ number_format($project['sum_gross_income'] + 0 ?? '-', 2, '.', ' ') }}</td>
                                        @endrole
                                        <td>
                                            <input class="form-control form-control-sm"
                                                   style="width: 100px"
                                                   type="string"
                                                   onchange="editPlanGrossIncome(this, '{{ route('project.partial_update', ['id'=>$project['id']]) }}')"
                                                   name="plan_gross_income"
                                                   value="{{$project['plan_gross_income']}}">
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            @forelse ($project['projectAuthor'] as $author)
                                                <div class="nowrap">{{ $author['full_name'] }}</div>
                                            @empty
                                                <span
                                                    style="font-style: italic; font-size: 12px; color: rgba(0,0,0,0.53);">Пусто</span>
                                            @endforelse
                                        </td>

                                        <td style="padding: 0 10px 0 12px!important">{{ $project['price_client'] ?? ''}}</td>
                                        <td style="padding: 0 10px 0 12px!important">{{ $project['price_author'] ?? ''}}</td>

                                        <td style="padding: 0 10px 0 12px!important">{{ ((int)$project['price_client'] - (int)$project['price_author'])}}</td>

                                        <td style="padding: 0 10px 0 12px!important">
                                            @if($project['contract'] == 0)
                                                Нет
                                            @else
                                                Да
                                            @endif
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            @foreach($project['projectClients'] as $client )
                                                @foreach($client['socialNetwork'] as $social_network)
                                                    {{$social_network['name'] ?? ''}} <br>
                                                @endforeach
                                            @endforeach
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">{{$project['projectTheme']['name'] ?? ''}}
                                        </td>

                                        <td style="padding: 0 10px 0 12px!important">@if($project['nds'] == 0)
                                                Нет
                                            @else
                                                Да
                                            @endif
                                        </td>

                                        @role('Администратор')
                                        <td style="padding: 0 10px 0 12px!important">{{Illuminate\Support\Carbon::parse($project['start_date_project'])->format('d.m.Y')}}</td>
                                        <td>{{$project['projectUser']['full_name'] ?? '------'}}</td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            <div class="form-group col-12 d-flex justify-content-between destroy">
                                                <a href="{{route('project.destroy',['project' => $project['id']])}}"
                                                   class="btn btn-sm btn-danger" onclick="confirmDelete()">
                                                    <i class="fas fa-trash-alt"></i></a>
                                        </td>
                                        @endrole
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="w-100 d-flex justify-content-center mt-3">
                            {{ $projects->appends(request()->input())->links('vendor.pagination.custom')  }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script src="{{asset('js/project.js')}}"></script>

    <script>
        window.confirmDelete = function () {
            var res = confirm('Вы действительно хотите удалить этот проект?')
            if (!res) {
                event.preventDefault();
            }
        }
        $('input[name="plan_gross_income"]').mask('# ### ###', {reverse: true});
    </script>
@endsection


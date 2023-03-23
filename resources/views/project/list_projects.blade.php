@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    {{--    <style>--}}
    {{--        table {--}}
    {{--            counter-reset: rowNumber;--}}
    {{--        }--}}

    {{--        table tbody tr {--}}
    {{--            counter-increment: rowNumber;--}}
    {{--        }--}}

    {{--        table tr td:first-child::before {--}}
    {{--            content: counter(rowNumber);--}}
    {{--            min-width: 1em;--}}
    {{--            margin-right: 0.5em;--}}
    {{--        }--}}
    {{--    </style>--}}
@endsection
@section('content')
    <div class="row p-0s">
        <div class="col-12">
            {{--        ФИЛЬТР--}}
            <div class="col-12 mb-3">
                @include('Answer.custom_response')
                @include('Answer.validator_response')
                <div class="w-100 shadow border rounded p-3">
                    <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                            class="fa fa-search search-icon mr-2"></i>Поиск
                    </div>
                    <form action="{{ route('project.index') }}" method="GET" class="check__field">
                        @csrf
                        <div class="row m-0" id="search">
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
                                                <option value="{{$manager['id']}}">{{$manager['full_name']}}</option>
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
                                    <input type="text" class="form-control form-control-sm" name="price_per"
                                           value="{{ request()->price_per ?? '' }}">
                                </div>

                                {{--                                <div class="form-group col-12 col-md-4 col-lg-3">--}}
                                {{--                                    <label for="" class="form-label">Цена заказчика</label>--}}
                                {{--                                    <div class="input-group input-group-sm">--}}
                                {{--                                        <input class="form-control form-control-sm" type="number" step="0.01" min="0.01"--}}
                                {{--                                               name="price_client"--}}
                                {{--                                               value="{{ request()->price_client ?? '' }}">--}}
                                {{--                                        <div class="input-group-append">--}}
                                {{--                                            <span class="input-group-text" id="basic-addon2">РУБ</span>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}

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
                                        <option value="">Не выбрано</option>
                                        <option value="1">Да</option>
                                        <option value="0">Нет</option>
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Состояние</label>
                                    <select class="form-control select-2" multiple
                                            name="status_id[]" id="">
                                        <option value="">Не выбрано</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{$status['id']}}"
                                                    @if($status['id'] == request()->status_id)
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
                                    <label class="form-label">Диапазон добавления</label>
                                    <div class="input-group">
                                        <input type="date" name="date_from" class="form-control form-control-sm"
                                               value="{{ request()->date_from ?? "" }}">
                                        <input type="date" name="date_before" class="form-control form-control-sm"
                                               value="{{ request()->date_before ?? "" }}">
                                    </div>
                                </div>

                                <div class="col-12 p-0">
                                    <div class="form-group col-12">
                                        <div class="w-100 d-flex justify-content-end">
                                            @if(!empty(request()->all() && count(request()->all())) > 0)
                                                <a href="{{ route('project.index') }}"
                                                   class="btn btn-sm btn-danger mr-3">Сбросить
                                                    фильтр</a>
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
                            <div class="text-16">Найдено записей: {{ count($projects) }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables"
                                   class="display table table-hover table-head-bg-info table-center table-cut">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th></th>
                                    <th></th>
                                    <th>@include('components.table.sort', ['title' => 'Менеджер', 'column' => 'users|full_name'] )</th>
                                    <th>Заказчик(и)</th>
                                    <th>Контакт</th>
                                    <th>@include('components.table.sort', ['title' => 'Проект', 'column' => 'project_name'] )</th>
                                    <th>@include('components.table.sort', ['title' => 'Тип текста', 'column' => 'styles|name'] )</th>
                                    <th>@include('components.table.sort', ['title' => 'Цена за 1000', 'column' => 'price_per'] )</th>
                                    <th>@include('components.table.sort', ['title' => 'Тема', 'column' => 'themes|name'] )</th>
                                    <th>@include('components.table.sort', ['title' => 'Договор', 'column' => 'contract'] )</th>
                                    <th>@include('components.table.sort', ['title' => 'Состояние', 'column' => 'statuses|name'] )</th>
                                    <th>Дата последнего прописывания</th>
                                    <th>Комментарий</th>
                                    <th>Автор</th>
                                    <th>@include('components.table.sort', ['title' => 'Цена автора', 'column' => 'price_author'] )</th>
                                    <th>Дата поступления</th>
                                    @role('Администратор')
                                    <th>Удалить</th>@endrole
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($projects as $key => $project)
                                    <tr style="background-color: {{ $project['project_status']['color'] ?? "" }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td><input type="checkbox" name="check" @if((bool)$project['check']) checked
                                                   @endif onchange="editCheckProject(this, '{{ route('project.partial_update', ['id'=> $project['id']]) }}')">
                                        </td>
                                        <td><a href="{{route('project.edit',['project'=> $project['id']])}}">Открыть</a>

                                        </td>
                                        <td><textarea disabled
                                                      style="border: none; border-radius: 10px; background-color: rgba(255,255,255,0);"
                                            >{{$project['project_user']['full_name'] ?? '------'}}</textarea></td>
                                        <td>
                                                @forelse ($project['project_clients'] as $client)
                                                    {{ $client['name'] }}
                                                @empty
                                                    -
                                                @endforelse
                                        <td>{{$project['project_clients'][0]['contact_info'] ?? '------'}}</td>
                                        <td>{{$project['project_name'] ?? '------'}}</td>
                                        <td>{{$project['project_style']['name'] ?? '------'}}</td>
                                        {{--                                        @dd($project)--}}

                                        <td>{{$project['price_per'] ?? '------'}}</td>
                                        <td>{{$project['project_theme']['name'] ?? '------'}}</td>
                                        <td>@if($project['contract'] == 0)
                                                Нет
                                            @else
                                                Да
                                            @endif</td>
                                        <td style="min-width: 130px;">
                                            <div class="d-flex align-items-center">
                                                <select class="form-select form-select-sm mr-1" id="edit_status_project"
                                                        onchange="editStatusProject(this, '{{ route('project.partial_update', ['id'=> $project['id']]) }}')">
                                                    @foreach ($statuses as $status)
                                                        <option value="{{$status['id']}}"
                                                                @if($status['id'] == $project['status_id']) selected @endif
                                                        >{{$status['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <input class="form-control form-control-sm"
                                                       onchange="editDateLastChangeProject(this, '{{ route('project.partial_update', ['id'=>$project['id']]) }}')"
                                                       name="date_last_change" type="date"
                                                       value="{{$project['date_last_change']}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <textarea style="width: 100%; height: 100%; border: none"
                                                          onchange="editCommentProject(this, '{{ route('project.partial_update', ['id'=>$project['id']]) }}')"
                                                          type="text"
                                                >{{$project['comment']}}</textarea>
                                            </div>
                                        </td>
                                        <td>
                                            @forelse ($project['project_author'] as $author)
                                                <div class="nowrap">{{ $author['full_name'] }}</div>
                                            @empty
                                                ------
                                            @endforelse

                                        </td>
                                        <td>{{$project['price_author'] ?? '------'}}</td>
                                        <td>{{Illuminate\Support\Carbon::parse($project['start_date_project'])->format('d.m.Y')}}</td>
                                        @role('Администратор')
                                        <td>
                                            <div class="form-group col-12 d-flex justify-content-between destroy">
                                                <a href="{{route('project.destroy',['project' => $project['id']])}}"
                                                   class="btn btn-sm btn-outline-danger" onclick="confirmDelete()"><i
                                                        class="fas fa-minus"></i></a>
                                            </div>
                                        </td>
                                        @endrole
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('custom_js')
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="{{asset('js/select2.js')}}"></script>
            <script src="{{asset('js/project.js')}}"></script>

            <script>
                window.confirmDelete = function () {
                    var res = confirm('Вы действительно хотите удалить этот проект?')
                    if (!res) {
                        event.preventDefault();
                    }
                }
            </script>
@endsection


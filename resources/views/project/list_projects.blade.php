@extends('layout.markup')
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
                <div class="w-100 shadow border rounded p-3">
                    <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                            class="fa fa-search search-icon mr-2"></i>Поиск
                    </div>
                    <form action="{{ route('project.index') }}" method="GET" class="check__field">
                        @csrf
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
                                    <input type="text" class="form-control form-control-sm" name="price_per"
                                           value="{{ request()->price_per ?? '' }}">
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

                                <div class="col-12 p-0">
                                    <div class="form-group col-12">
                                        <div class="w-100 d-flex justify-content-end">
                                            {{--                                            @if(!empty(request()->all() && count(request()->all())) > 0)--}}
                                            {{--                                                <a href="{{ route('project.index') }}"--}}
                                            {{--                                                   class="btn btn-sm btn-danger mr-3">Сбросить--}}
                                            {{--                                                    фильтр</a>--}}
                                            {{--                                            @endif--}}
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
                                    <th>
                                        <a href="{{ route('project.delete_checkboxes') }}" type="submit"
                                           class="text-white ">
                                            ✖
                                        </a>
                                    </th>
                                    <th></th>
                                    @role('Администратор')
                                    <th>@include('components.table.sort', ['title' => 'Менеджер', 'column' => 'users|full_name', 'routeName' => 'project.index'] )</th>
                                    @endrole
                                    <th>@include('components.table.sort', ['title' => 'Проект', 'column' => 'project_name', 'routeName' => 'project.index'] )</th>
                                    <th>Заказчик(и)</th>
                                    <th>Дата последнего прописывания</th>
                                    <th>@include('components.table.sort', ['title' => 'Состояние', 'column' => 'statuses|name', 'routeName' => 'project.index'] )</th>
                                    <th style="min-width: 300px !important;">Комментарий</th>
                                    <th>Автор</th>
                                    <th>@include('components.table.sort', ['title' => 'Цена заказчика', 'column' => 'price_client', 'routeName' => 'project.index'] )</th>
                                    <th>@include('components.table.sort', ['title' => 'Цена автора', 'column' => 'price_author', 'routeName' => 'project.index'] )</th>

                                    <th>@include('components.table.sort', ['title' => 'Дог', 'column' => 'contract', 'routeName' => 'project.index'] )</th>
                                    <th>Место ведения диалога</th>
                                    <th>Контакт</th>
                                    <th>@include('components.table.sort', ['title' => 'Тема', 'column' => 'themes|name', 'routeName' => 'project.index'] )</th>
                                    <th>@include('components.table.sort', ['title' => 'Приоритетность', 'column' => 'styles|name', 'routeName' => 'project.index'] )</th>
                                    @role('Администратор')
                                    <th>Дата поступления</th>
                                    @endrole


                                    {{--                                    <th>@include('components.table.sort', ['title' => 'Цена за 1000', 'column' => 'price_per'] )</th>--}}
                                    @role('Администратор')
                                    <th>Удалить</th>
                                    @endrole
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($projects as $key => $project)
                                    <tr>
                                        {{--                                        <td style="padding: 0 10px 0 12px!important">{{ $key + 1 }}</td>--}}
                                        <td style="padding: 0 10px 0 12px!important"><input type="checkbox" name="check"
                                                                                            @if((bool)$project['check']) checked
                                                                                            @endif onchange="editCheckProject(this, '{{ route('project.partial_update', ['id'=> $project['id']]) }}')">
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important"><a
                                                href="{{route('project.edit',['project'=> $project['id']])}}"><i
                                                    class="fas fa-grip-horizontal"></i></a>

                                        </td>
                                        @role('Администратор')
                                        <td style="padding: 0 10px 0 12px!important"><textarea disabled
                                                                                               style="border: none; width: 100px; border-radius: 10px; background-color: rgba(255,255,255,0);"
                                            >{{$project['projectUser']['full_name'] ?? '------'}}</textarea></td>
                                        @endrole
                                        <td style="padding: 0 10px 0 12px!important">{{$project['project_name'] ?? '------'}}</td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            @forelse ($project['projectClients'] as $client)
                                                {{ $client['name'] }}
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            <div>
                                                <input class="form-control form-control-sm"
                                                       onchange="editDateLastChangeProject(this, '{{ route('project.partial_update', ['id'=>$project['id']]) }}')"
                                                       name="date_last_change" type="date"
                                                       value="{{$project['date_last_change']}}">
                                            </div>
                                        </td>
                                        <td style=" min-width: 170px;">
                                            <div class="d-flex align-items-center">
                                                <select
                                                    style=" background-color: {{ $project['projectStatus']['color'] ?? "" }} ;"
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
                                            @forelse ($project['projectAuthor'] as $author)
                                                <div class="nowrap">{{ $author['full_name'] }}</div>
                                            @empty
                                                <span
                                                    style="font-style: italic; font-size: 12px; color: rgba(0,0,0,0.53);">Пусто</span>
                                            @endforelse
                                        </td>
                                        {{--@dd($project)--}}
                                        <td style="padding: 0 10px 0 12px!important">{{ $project['price_client'] ?? ''}}</td>
                                        <td style="padding: 0 10px 0 12px!important">{{ $project['price_author'] ?? ''}}</td>

                                        <td style="padding: 0 10px 0 12px!important">@if($project['contract'] == 0)
                                                Нет
                                            @else
                                                Да
                                            @endif</td>
                                        <td style="padding: 0 10px 0 12px!important">
                                            @foreach($project['projectClients'] as $client )
                                                @foreach($client['socialNetwork'] as $social_network)
                                                    {{$social_network['name'] ?? ''}} <br>
                                                @endforeach
                                            @endforeach
                                        </td>
                                        <td style="padding: 0 10px 0 12px!important">{{$project['project_clients'][0]['contact_info'] ?? '------'}}</td>
                                        <td style="padding: 0 10px 0 12px!important">{{$project['projectTheme']['name'] ?? ''}}
                                        </td>

                                        <td style="padding: 0 10px 0 12px!important">{{$project['projectStyle']['name'] ?? '------'}}</td>
                                        @role('Администратор')
                                        <td style="padding: 0 10px 0 12px!important">{{Illuminate\Support\Carbon::parse($project['start_date_project'])->format('d.m.Y')}}</td>
                                        <td style="padding: 0 10px 0 12px!important">
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


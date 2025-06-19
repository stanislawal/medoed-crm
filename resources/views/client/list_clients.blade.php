@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')

    <div class="row p-0s">
        <div class="col-12">
            <div class="w-100 shadow border rounded p-3 mb-3 bg-white">
                <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                        class="fa fa-search search-icon mr-2"></i>Поиск
                </div>
                <form action="{{ route('client.index') }}" method="GET" class="check__field">
                    @csrf
                    <div class="row m-0" id="search" @if(empty(request()->all())) style="display: none;" @endif>
                        <div class="w-100 row m-0 py-3">

                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label for="" class="form-label">Имя</label>
                                <input type="text" class="form-control form-control-sm" name="name"
                                       value="{{ request()->name ?? '' }}">
                            </div>

                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label class="form-label">Проект</label>
                                <select multiple class="form-select form-select-sm select-2"
                                        name="project_id[]">
                                    <option value="">Не выбрано</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project['id'] }}"

                                                @if(in_array($project['id'], request()->project_id ?? [])) selected @endif>
                                            {{ $project['project_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label for="" class="form-label">Источник поступления</label>
                                <select name="source_client_id" id="source_client_id" class="form-select form-select-sm">
                                    <option value="">Не выбрано</option>
                                    @foreach($sourceClients as $item)
                                        <option value="{{ $item['id'] }}" @if($item['id'] == request()->source_client_id) selected @endif>{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 p-0">
                            <div class="form-group col-12">
                                <div class="w-100 d-flex justify-content-end">
                                    @if(!empty(request()->all() && count(request()->all())) > 0)
                                        <a href="{{ route('client.index') }}" class="btn btn-sm btn-danger mr-3">Сбросить
                                            фильтр</a>
                                    @endif
                                    <button class="btn btn-sm btn-success">Искать</button>
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
                        <h4 class="card-title">Заказчики</h4>
                        <div>Всего записей: <strong>{{ $clients->total() }}</strong></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="w-100 d-flex justify-content-center mb-3">
                        {{ $clients->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                    <div class="table-responsive">
                        <table id="basic-datatables"
{{--                               class="display table  table-hover table-head-bg-info">--}}
                               class="display table table-hover table-head-bg-info table-center table-cut">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Проект</th>
                                <th>Сфера деятельности</th>
                                <th>Имя компании</th>
                                <th>Контактная инф.</th>
                                <th>Соц.сеть</th>
                                <th>Информация о работе команды</th>
                                <th>Источник поступления</th>
                                <th>Удалить</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td style="padding: 0 10px 0 12px!important">
                                        <a href="{{route('client.edit',['client'=> $client['id']])}}"><i
                                                class="fas fa-grip-horizontal"></i>
                                        </a>
                                    </td>
                                    <td>{{ $client['id'] }}</td>
                                    <td>{{$client['name'] ?? '-'}}</td>
                                    <td>
                                        @foreach($client['projectClients'] as $item)
                                            <strong>·</strong> {{ $item['project_name'] }} <br>
                                        @endforeach
                                    </td>
                                    <td>{{$client['scope_work'] ?? '-'}}</td>
                                    <td>{{$client['company_name'] ?? '-'}}</td>
                                    <td>{{$client['contact_info'] ?? '-' }}</td>

                                    <td>
                                        @foreach ($client['socialNetwork'] as $socialnetrowk)
                                            <span
                                                class="badge bg-primary">{{ $socialnetrowk['name'] }}: {{ $socialnetrowk['pivot']['description'] }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $client['info_work_team'] }}</td>
                                    <td>{{ $client['sourceClient']['name'] ?? '' }}</td>
                                    <td>
                                        <div class="form-group col-12 d-flex justify-content-between destroy">
                                            <a href="{{route('client.destroy',['client' => $client['id']])}}"
                                               class="btn btn-sm btn-danger" onclick="confirmDelete()">
                                                <i class="fas fa-minus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100 d-flex justify-content-center mt-3">
                        {{ $clients->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_js')
    <script>   window.confirmDelete = function () {
            var res = confirm('Вы действительно хотите удалить пользователя?')
            if (!res) {
                event.preventDefault();
            }
        }</script>

    <script
        src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script src="{{asset('js/article.js')}}"></script>
@endsection

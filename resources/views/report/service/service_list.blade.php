@extends('layout.markup')

@section('title')
    Свод по услугам
@endsection

@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')
    {{--    ФИЛЬТР --}}
    <div class="mb-3">
        @include('Answer.custom_response')
        @include('Answer.validator_response')
        <div class="w-100 shadow border rounded p-3 bg-white">
            <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i class="fa fa-search search-icon mr-2"></i>Поиск
            </div>
            <form action="" method="GET" class="check__field">
                @csrf
                <div class="row m-0" id="search">
                    <div class="w-100 row m-0 py-3">

                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Проект</label>
                            <select class="form-control border form-control-sm select-2" title="Пожалуйста, выберите"
                                    name="project_id">
                                <option value=" " selected>Не выбрано</option>
                                @foreach ($projects as $item)
                                    <option @if ($item['id'] == request()->project_id) selected @endif
                                    value="{{ $item['id'] }}">{{ $item['project_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--    ФИЛЬТР --}}
    @role('Администратор')
    <div class="mb-2">
        <div class="row">
            <div class="col-12 ">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>---</strong></div>
                            <div class="text-12 nowrap-dot">Значение:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>---</strong></div>
                            <div class="text-12 nowrap-dot">Значение:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>---</strong></div>
                            <div class="text-12 nowrap-dot">Значение:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>---</strong></div>
                            <div class="text-12 nowrap-dot">Значение:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole

    {{--    ТАБЛИЦА --}}
    <div class="w-100 shadow border rounded bg-white">
        <div class=>
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Общий свод по услугам</h4>
                    <div>Всего записей: <strong>{{ $reports->total() }}</strong></div>
                </div>
            </div>
            <div class="card-body">
                <div class="w-100 d-flex justify-content-center mb-3">
                    {{ $reports->appends(request()->input())->links('vendor.pagination.custom') }}
                </div>
                <div class="table-responsive">
                    <table id="basic-datatables"
                           class="display table table-hover table-head-bg-info table-center table-cut">
                        <thead>

                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Проекта</th>
                            <th>Клиент</th>
                            <th>Тема проекта</th>
                            <th>Отчетная дата</th>

                            <th>М-ц работы</th>
                            <th>Способ оплаты</th>
                            <th>Общ.сум.д</th>
                            <th>Начис.за.м</th>
                            <th>Спец. в проекте</th>
                            <th>Менеджер</th>
                            <th>Регион продвижения</th>
                            <th>Планы</th>
                            <th>Часы</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($reports as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('report_service.show', ['project_id' => $item['id']]) }}">
                                        <i class="fas fa-grip-horizontal"></i>
                                    </a>
                                </td>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['project_name'] }}</td>
                                <td>
                                    @foreach ($item['projectClients'] as $client)
                                        {{ $client['name'] }}
                                    @endforeach
                                </td>
                                <td>{{ $item['project_theme_service'] }}</td>
                                <td>{{ $item['reporting_data'] }}</td>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $item['region'] }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="w-100 d-flex justify-content-center mt-3">
                    {{ $reports->appends(request()->input())->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
@endsection

@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')
    {{--    ФИЛЬТР--}}
    {{--    <div class="mb-3">--}}
    {{--        @include('Answer.custom_response')--}}
    {{--        @include('Answer.validator_response')--}}
    {{--        <div class="w-100 shadow border rounded p-3">--}}
    {{--            <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i--}}
    {{--                    class="fa fa-search search-icon mr-2"></i>Поиск--}}
    {{--            </div>--}}
    {{--            <form action="" method="GET" class="check__field">--}}
    {{--                @csrf--}}
    {{--                <div class="row m-0" id="search">--}}
    {{--                    <div class="w-100 row m-0 py-3">--}}
    {{--                        <div class="form-group col-12 col-md-4 col-lg-3">--}}
    {{--                            <label for="" class="form-label">Менеджер </label>--}}
    {{--                            <select class="form-control form-control-sm" name="manager_id">--}}
    {{--                                <option value="">Не выбрано</option>--}}
    {{--                                @foreach ($managers as $manager)--}}
    {{--                                    <option @if($manager['id'] == request()->manager_id) selected--}}
    {{--                                            @endif value="{{$manager['id']}}">{{$manager['full_name']}}</option>--}}
    {{--                                @endforeach--}}
    {{--                            </select>--}}
    {{--                        </div>--}}
    {{--                        <div class="form-group col-12 col-md-4 col-lg-3">--}}
    {{--                            <label for="" class="form-label">Долг</label>--}}
    {{--                            <div class="input-group">--}}
    {{--                                <input type="number" step="0.01" class="form-control form-control-sm" name="duty_from"--}}
    {{--                                       value="{{ request()->duty_from ?? '' }}" placeholder="От">--}}
    {{--                                <input type="number" step="0.01" class="form-control form-control-sm" name="duty_to"--}}
    {{--                                       value="{{ request()->duty_to ?? '' }}" placeholder="До">--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="form-group col-12 col-md-4 col-lg-3">--}}
    {{--                            <label for="" class="form-label">Объем ЗБП </label>--}}
    {{--                            <div class="input-group">--}}
    {{--                                <input type="number" step="0.01" class="form-control form-control-sm"--}}
    {{--                                       name="sum_without_space_from"--}}
    {{--                                       value="{{ request()->sum_without_space_from ?? '' }}" placeholder="От">--}}
    {{--                                <input type="number" step="0.01" class="form-control form-control-sm"--}}
    {{--                                       name="sum_without_space_to" value="{{ request()->sum_without_space_to ?? '' }}"--}}
    {{--                                       placeholder="До">--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="form-group col-12 col-md-4 col-lg-3">--}}
    {{--                            <label for="" class="form-label">Маржа</label>--}}
    {{--                            <div class="input-group">--}}
    {{--                                <input type="number" step="0.01" class="form-control form-control-sm" name="profit_from"--}}
    {{--                                       value="{{ request()->profit_from ?? '' }}" placeholder="От">--}}
    {{--                                <input type="number" step="0.01" class="form-control form-control-sm" name="profit_to"--}}
    {{--                                       value="{{ request()->profit_to ?? '' }}" placeholder="До">--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="form-group col-12 col-md-4 col-lg-3">--}}
    {{--                            <label for="" class="form-label">Срок в работе (дни)</label>--}}
    {{--                            <div class="input-group">--}}
    {{--                                <input type="number" step="0.01" class="form-control form-control-sm"--}}
    {{--                                       name="date_diff_from" value="{{ request()->date_diff_from ?? '' }}"--}}
    {{--                                       placeholder="От">--}}
    {{--                                <input type="number" step="0.01" class="form-control form-control-sm"--}}
    {{--                                       name="date_diff_to" value="{{ request()->date_diff_to ?? '' }}" placeholder="До">--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="form-group col-12 col-md-4 col-lg-3">--}}
    {{--                            <label for="" class="form-label">Проект</label>--}}
    {{--                            <select class="form-control border form-control-sm select-2"--}}
    {{--                                    title="Пожалуйста, выберите"--}}
    {{--                                    name="project_id">--}}
    {{--                                <option value=" " selected>Не выбрано</option>--}}
    {{--                                @foreach($project as $project_info)--}}
    {{--                                    <option @if($project_info['id'] == request()->project_id) selected--}}
    {{--                                            @endif value="{{$project_info['id']}}">{{$project_info['project_name']}}</option>--}}
    {{--                                @endforeach--}}
    {{--                            </select>--}}
    {{--                        </div>--}}
    {{--                        <div class="form-group col-12 col-md-4 col-lg-3">--}}
    {{--                            <label for="" class="form-label">Заказчик</label>--}}
    {{--                            <select class="form-control border form-control-sm select-2"--}}
    {{--                                    title="Пожалуйста, выберите"--}}
    {{--                                    name="client_id">--}}
    {{--                                <option value="">Не выбрано</option>--}}
    {{--                                @foreach ($clients as $client)--}}
    {{--                                    <option @if($client['id'] == request()->client_id) selected @endif--}}
    {{--                                    value="{{$client['id']}}">{{$client['name']}}</option>--}}
    {{--                                @endforeach--}}
    {{--                            </select>--}}
    {{--                        </div>--}}

    {{--                        <div class="form-group col-12 col-md-4 col-lg-3">--}}
    {{--                            <label for="" class="form-label">Даты</label>--}}
    {{--                            <div class="input-group">--}}
    {{--                                <input type="date" class="form-control form-control-sm" name="start_date"--}}
    {{--                                       value="{{ request()->start_date ?? now()->startOfMonth()->format('Y-m-d') }}" placeholder="От">--}}
    {{--                                <input type="date" class="form-control form-control-sm" name="end_date"--}}
    {{--                                       value="{{ request()->end_date ?? now()->format('Y-m-d') }}" placeholder="До">--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                        <div class="col-12 p-0">--}}
    {{--                            <div class="form-group col-12">--}}
    {{--                                <div class="w-100 d-flex justify-content-end">--}}

    {{--                                    @if(!empty(request()->all() && count(request()->all())) > 0)--}}
    {{--                                        <a href="{{ route('report_client.index') }}"--}}
    {{--                                           class="btn btn-sm btn-danger mr-3">Сбросить--}}
    {{--                                            фильтр</a>--}}
    {{--                                    @endif--}}
    {{--                                    <button class="btn btn-sm btn-success">Искать</button>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </form>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    ФИЛЬТР--}}

    <div class="mb-2">
        <div class="row">
            <div class="col-12 col-md-9">
                <div class="row">
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>21033</strong></div>
                            <div class="text-12 nowrap-dot">Маржа:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>76</strong></div>
                            <div class="text-12 nowrap-dot">Количество рабочих дней:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>345346</strong></div>
                            <div class="text-12 nowrap-dot">Общая сумма гонораров:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>34534534</strong></div>
                            <div class="text-12 nowrap-dot">Итого к выплате:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>234234</strong></div>
                            <div class="text-12 nowrap-dot">Валовый доход:</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="w-100">
                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <div style="font-size: 40px">$</div>
                                <div class="pl-3"><span class="text-14">USD:</span></div>
                                <div class="pl-2 text-18">{{$rates->where('id_currency', 2)->first()->rate ?? ""}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 40px">€</div>
                            <div class="pl-3"><span class="text-14">EUR:</span></div>
                            <div class="pl-2 text-18">{{$rates->where('id_currency', 3)->first()->rate ?? ""}}</div>
                        </div>
                    </div>

                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 38px">₴</div>
                            <div class="pl-3"><span class="text-14">UAH:</span></div>
                            <div class="pl-2 text-18">{{$rates->where('id_currency', 4)->first()->rate ?? ""}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    ТАБЛИЦА--}}
    <div class="w-100 shadow border rounded">
        <div class=>
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Общий свод по авторам</h4>
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
                            <th>Банк</th>
                            <th>Автор</th>
                            <th>Гонорар</th>
                            <th>К выплате</th>
                            <th>ВД</th>
                            <th>Маржа</th>
                            <th>Ср. цена</th>
                            <th>Норма/день</th>
                            <th>Факт V/день</th>
                            <th>Недозагрузка</th>
                        </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td></td>
                                <td><a href="#">
                                        <i class="fas fa-grip-horizontal"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script src="{{asset('js/project.js')}}"></script>
@endsection

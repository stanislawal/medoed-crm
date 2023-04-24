@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')

    {{--    ФИЛЬТР--}}
    <div class="mb-3">
        @include('Answer.custom_response')
        @include('Answer.validator_response')
        <div class="w-100 shadow border rounded p-3">
            <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                    class="fa fa-search search-icon mr-2"></i>Поиск
            </div>
            <form action="" method="GET" class="check__field">
                @csrf
                <div class="row m-0" id="search">
                    <div class="w-100 row m-0 py-3">
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Менеджер </label>
                            <input type="text" class="form-control form-control-sm" name="id"
                                   value="">
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Долг</label>
                            <input type="text" class="form-control form-control-sm" name="id"
                                   value="">
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Объем ЗБП </label>
                            <input type="text" class="form-control form-control-sm" name="id"
                                   value="">
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Маржа</label>
                            <input type="text" class="form-control form-control-sm" name="id"
                                   value="">
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Срок в работе</label>
                            <input type="text" class="form-control form-control-sm" name="id"
                                   value="">
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Проект</label>
                            <input type="text" class="form-control form-control-sm" name="id"
                                   value="">
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Заказчик</label>
                            <input type="text" class="form-control form-control-sm" name="id"
                                   value="">
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
    {{--    ФИЛЬТР--}}


    <div class="mb-2">
        <div class="row">
            <div class="col-12 col-md-9">
                <div class="row">
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{$statistics['duty']}} ₽</strong></div>
                            <div class="text-12 nowrap-dot">Общий долг:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{$statistics['sum_without_space']}}</strong></div>
                            <div class="text-12 nowrap-dot">Общий объем ЗБП:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{$statistics['sum_gross_income']}}</strong></div>
                            <div class="text-12 nowrap-dot">ВД:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{$statistics['profit']}}</strong></div>
                            <div class="text-12 nowrap-dot">Маржа:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{number_format($statistics['middle_check'], 2,'.', '')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Средний чек:</div>
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
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Общий свод по заказчикам</h4>
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

                            <th>Состояние</th>
                            <th>Долг</th>
                            <th>Проект</th>
                            <th>Заказчик</th>
                            <th>Объем ЗБП</th>
                            <th>ВД</th>
                            <th>Маржа</th>
                            <th>Менеджер</th>
                            <th>Сроки оплаты</th>
                            <th>Срок в работе</th>
                            <th>Цена проекта</th>
                            <th>Цена автора</th>
                            <th>Знаки в день</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reports as $item)
                            <tr>
                                <td>{{$item['id']}}</td>
                                <td><a href="{{route('report_client.show')}}"><i class="fas fa-grip-horizontal"></a></td>
                                <td class="text-center">
                                    <span class="badge text-dark" style="background-color: {{ $item['project_status']['color'] }} ">{{$item['project_status']['name'] ?? ''}}</span>
                                </td>
                                <td>{{$item['duty'] ?? '-'}}</td>
                                <td>{{$item['project_name'] ?? '-'}}</td>
                                <td>@foreach($item['project_clients'] as $client)
                                    {{$client['name']}}
                                @endforeach</td>
                                <td>{{$item['sum_without_space']+0 ?? '-'}}</td>
                                <td>{{$item['sum_gross_income']+0 ?? '-'}}</td>
                                <td>{{$item['profit'] ?? '-'}}</td>
                                <td>{{$item['project_user']['full_name'] ?? '-'}}</td>
                                <td>{{$item['payment_terms'] ?? '-'}}</td>
                                <td>{{$item['date_diff'].' дней' ?? '-'}}</td>
                                <td>{{$item['sum_price_client'] ?? '-'}}</td>
                                <td>{{$item['sum_price_author'] ?? '-'}}</td>
                                <td>{{$item['symbol_in_day'] ?? '-'}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{--    ТАБЛИЦА--}}
{{--        @dd($reports)--}}
            @endsection

            @section('custom_js')
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script src="{{asset('js/select2.js')}}"></script>
                <script src="{{asset('js/project.js')}}"></script>
@endsection

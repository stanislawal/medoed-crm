@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="mb-2">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24"><strong>{{ $report[0]['project_name'] }}</strong></div>
                            <div class="text-12 nowrap-dot">Проект:</div>
                        </div>
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ collect($payment)->sum('amount') }}</strong></div>
                            <div class="text-12 nowrap-dot">Оплата:</div>
                        </div>
                    </div>
                    {{--                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">--}}
                    {{--                        <div class="px-3 py-2 shadow border bg-white rounded">--}}
                    {{--                            <div class="text-24"><strong>{{ collect($payment)->sum('count_operation') }}</strong></div>--}}
                    {{--                            <div class="text-12 nowrap-dot">Кол-во заявок оплаты:</div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">

                            <div class="text-24"><strong>
                                    @foreach($clients as $client)
                                        {{ $client['name'] }}
                                    @endforeach
                                </strong></div>
                            <div class="text-12 nowrap-dot">Заказчик:</div>
                        </div>
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24"><strong>{{ $report->sum('price_client') }}</strong></div>
                            <div class="text-12 nowrap-dot">Цена проекта:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24"><strong>{{ $report->sum('without_space') }}</strong></div>
                            <div class="text-12 nowrap-dot">Сдано ЗБП:</div>
                        </div>
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ $report->sum('gross_income') }}</strong></div>
                            <div class="text-12 nowrap-dot">Сумма ВД:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4  col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24"><strong>{{ $report->sum('price_client') - collect($payment)->sum('amount') }}</strong></div>
                            <div class="text-12 nowrap-dot">Долг: </div>
                        </div>
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ $report->sum('margin') }}</strong></div>
                            <div class="text-12 nowrap-dot">Маржа:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-100 shadow border rounded">
        <div class="card mb-0">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Информация о статьях проекта</h4>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables"
                           class="display table table-hover table-head-bg-info table-center table-cut">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Автор</th>
                            <th>Дата сдачи статьи</th>
                            <th>Название статьи</th>
                            <th>ЗБП</th>
                            <th>Цена заказчика</th>
                            <th>Сумма</th>
                            <th>Цена автора</th>
                            <th>Маржа</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($report as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td> @forelse($item['article_author'] as $author)
                                        <div class="badge bg-primary">{{ $author['full_name'] }}</div>
                                    @empty
                                        -
                                    @endforelse</td>
                                <td>{{ $item['end_date_project'] }}</td>
                                <td>{{ $item['article_name'] }}</td>
                                <td>{{ $item['without_space'] }}</td>
                                <td>{{ $item['price_client'] + 0 }}</td>
                                <td>{{($item['without_space'] / 1000) * $item['price_client'] + 0 }}</td>
                                <td>{{ $item['price_author'] + 0 }}</td>
                                <td>{{ $item['margin'] + 0 }}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

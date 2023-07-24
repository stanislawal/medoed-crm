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
                            <div class="text-24">
                                <strong>{{number_format(collect($payment)->sum('amount'), 2, '.', ' ')  }}</strong>
                            </div>
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
                            <div class="text-24">
                                <strong>{{number_format($report->sum('price_article'), 2, '.', ' ') }}</strong></div>
                            <div class="text-12 nowrap-dot">Цена проекта:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($report->sum('without_space'), 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Сдано ЗБП:</div>
                        </div>
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($report->sum('gross_income'), 2, '.', ' ')    }}</strong></div>
                            <div class="text-12 nowrap-dot">Сумма ВД:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4  col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($report->sum('price_client') - collect($payment)->sum('amount'), 2, '.', ' ') }}</strong>
                            </div>
                            <div class="text-12 nowrap-dot">Долг:</div>
                        </div>
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($report->sum('margin'), 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Маржа:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion accordion-flush mb-2" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    История оплат по проекту
                </button>
            </h2>
{{--            @dd($payment)--}}
            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Оплата</th>
                                <th>Дата оплаты</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>28.10.2023</td>
                                <td>700 <span>₽</span></td>
                            </tr>
                        </tbody>
                    </table>
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
                                <td>{{ $item['created_at'] }}</td>
                                <td>{{ $item['article_name'] }}</td>
                                <td>{{number_format($item['without_space'], 2, '.', ' ')  }}</td>
                                <td>{{number_format($item['price_client'] + 0, 2, '.', ' ')  }}</td>
                                <td>{{number_format($item['price_article'] + 0, 2, '.', ' ')  }}</td>
                                <td>{{number_format($item['price_author'] + 0, 2, '.', ' ')  }}</td>
                                <td>{{number_format($item['margin'] + 0, 2, '.', ' ')  }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

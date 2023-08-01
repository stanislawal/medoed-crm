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
                                <strong>{{number_format(($report->sum('price_article') - collect($payment)->sum('amount') + $duty), 2, '.', ' ') }}</strong>
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
    <div>
        <div>
            <form action="{{route('project.partial_update', ['id' => $projectId] )}}" method="post">
                @csrf
                <label class="mb-2" for="">Добавить долг</label>
                <input class="mb-2 form-control form-control-sm col-1" name="duty" step="0.01" type="number"
                       value="{{ $duty }}">
                <button class="mb-2 btn btn-sm btn-success">Обновить</button>
            </form>
        </div>
    </div>
    <div class="accordion accordion-flush mb-2 border bg-white round" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    <strong class="text-14 text-gray">История оплат по проекту</strong>
                </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Сбер А</th>
                            <th>Тинькофф А</th>
                            <th>Сбер Д</th>
                            <th>Сбер К</th>
                            <th>Приват</th>
                            <th>ЮМ</th>
                            <th>ВМЗ</th>
                            <th>Биржи</th>
                            <th>Дата оплаты</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($paymentHistory as $item)
                            <tr>
                                <td @if($item['sber_a'] > 0) class="text-primary fw-bold" @endif>{{ $item['sber_a'] }}
                                    <span>₽</span></td>
                                <td @if($item['tinkoff_a'] > 0) class="text-primary fw-bold" @endif>{{ $item['tinkoff_a'] }}
                                    <span>₽</span></td>
                                <td @if($item['sber_d'] > 0) class="text-primary fw-bold" @endif>{{ $item['sber_d'] }}
                                    <span>₽</span></td>
                                <td @if($item['sber_k'] > 0) class="text-primary fw-bold" @endif>{{ $item['sber_k'] }}
                                    <span>₽</span></td>
                                <td @if($item['privat'] > 0) class="text-primary fw-bold" @endif>{{ $item['privat'] }}
                                    <span>₽</span></td>
                                <td @if($item['um'] > 0) class="text-primary fw-bold" @endif>{{ $item['um'] }}
                                    <span>₽</span></td>
                                <td @if($item['wmz'] > 0) class="text-primary fw-bold" @endif>{{ $item['wmz'] }}
                                    <span>₽</span></td>
                                <td @if($item['birja'] > 0) class="text-primary fw-bold" @endif>{{ $item['birja'] }}
                                    <span>₽</span></td>
                                <td>{{ $item['created_at'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray">Нет операций</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion accordion-flush mb-2 border bg-white round" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    <strong class="text-14 text-gray">История статей по месяцам</strong>
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                   <div>
                       <a class="d-block" href="#">Аналитика за 07.2023</a>
                       <a class="d-block" href="#">Аналитика за 06.2023</a>
                       <a class="d-block" href="#">Аналитика за 05.2023</a>
                       <a class="d-block" href="#">Аналитика за 04.2023</a>
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
                                <td class="text-center">{{ $item['id'] }}</td>
                                <td> @forelse($item['article_author'] as $author)
                                        <div class="badge bg-primary">{{ $author['full_name'] }}</div>
                                    @empty
                                        -
                                    @endforelse</td>
                                <td class="text-center">{{ $item['created_at'] }}</td>
                                <td>{{ $item['article_name'] }}</td>
                                <td class="nowrap">{{number_format($item['without_space'], 2, '.', ' ')  }}</td>
                                <td class="nowrap">{{number_format($item['price_client'] + 0, 2, '.', ' ')  }}</td>
                                <td class="nowrap">{{number_format($item['price_article'] + 0, 2, '.', ' ')  }}</td>
                                <td class="nowrap">{{number_format($item['price_author'] + 0, 2, '.', ' ')  }}</td>
                                <td class="nowrap">{{number_format($item['margin'] + 0, 2, '.', ' ')  }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('title')
    Свод по услугам - проект {{ $project['project_name'] }}
@endsection

@section('content')
    @include('Window.Payment.create', ['setProject' => $project['id']])

    <div class="mb-2">
        <div class="mb-3">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
            <div class="w-100 shadow border rounded p-3 bg-white">

                <form action="" class="check__field">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3">
                            <input class="form-control form-control-sm" type="month" name="month"
                                   value="{{ request()->month ?? now()->format('Y-m') }}">
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <button class="btn btn-sm btn-success">Загрузить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24"><strong>{{ $project['project_name'] }}</strong></div>
                            <div class="text-12 nowrap-dot">Проект:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24">
                                <strong>
                                    @foreach ($project->projectClients as $client)
                                        {{ $client['name'] }}
                                    @endforeach
                                </strong>
                            </div>
                            <div class="text-12 nowrap-dot">Заказчик:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24"><strong>{{ number_format($remainderDuty, 2, '.', ' ') }}</strong></div>
                            <div class="text-12 nowrap-dot">Переносящийся долг:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div style="background-color: rgba(255,0,0,0.48);" class="px-3 py-2 shadow border mb-3 rounded">
                            <div class="text-24"><strong>{{ number_format($duty, 2, '.', ' ') }}</strong></div>
                            <div class="text-12 nowrap-dot">Долг:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24"><strong>{{ number_format($services->sum('accrual_this_month'), 2, '.', ' ') }}</strong></div>
                            <div class="text-12 nowrap-dot">Сумма начислений:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border mb-3 bg-white rounded">
                            <div class="text-24"><strong>{{ number_format(collect($payment)->sum('amount'), 2, '.', ' ') }}</strong></div>
                            <div class="text-12 nowrap-dot">Оплата:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion accordion-flush mb-2 border bg-white round" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    <strong class="text-14 text-gray">История оплат по проекту ({{ count($paymentHistory) }})</strong>
                </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="w-100 d-flex justify-content-end">
                        <div class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#create_payment">
                            Создать
                            заявку
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>ИП Алла (Т-Банк)</th>
                            <th>Т-Банк К.К</th>
                            <th>ИП Даша ( Т-Банк)</th>
                            <th>Сбер К.Г</th>
                            <th>Биржи</th>
                            <th>Дата оплаты</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($paymentHistory as $item)
                            <tr @if(!$item['mark']) style="background-color: #cd30304a" @endif>
                                <td>{{ $item['id'] }}</td>
                                <td class="nowrap @if($item['tinkoff_a'] > 0) text-primary fw-bold @endif">
                                    {{ $item['tinkoff_a'] }}
                                    <span>₽</span>
                                </td>
                                <td class="nowrap @if($item['tinkoff_k'] > 0) text-primary fw-bold @endif">
                                    {{ $item['tinkoff_k'] }}
                                    <span>₽</span>
                                </td>
                                <td class="nowrap @if($item['sber_d'] > 0) text-primary fw-bold @endif">
                                    {{ $item['sber_d'] }}
                                    <span>₽</span>
                                </td>
                                <td class="nowrap @if($item['sber_k'] > 0) text-primary fw-bold @endif">
                                    {{ $item['sber_k'] }}
                                    <span>₽</span>
                                </td>
                                <td class="nowrap @if($item['birja'] > 0) text-primary fw-bold @endif">
                                    {{ $item['birja'] }}
                                    <span>₽</span>
                                </td>
                                <td>{{ $item['date'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-gray">Нет операций</td>
                            </tr>
                        @endforelse
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
                    <h4 class="card-title">Информация об услугах проекта</h4>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables"
                           class="display table table-hover table-head-bg-info table-center table-cut">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Отдел</th>
                            <th>Услуга</th>
                            <th>Специалист</th>
                            <th>Общая сумма договора</th>
                            <th>Начислено в этом месяце</th>
                            <th>Дата создания</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($services as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <div class="select-2-custom-state-color"
                                             style="background-color: {{ $item->serviceType->color }}">
                                            {{ $item->serviceType->name }}
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item['name'] }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($item->specialists as $specialist)
                                            <div class="select-2-custom-state-color"
                                                 style="background-color: {{ $specialist->color }}">
                                                {{ $specialist->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ number_format($item['all_price'] + 0, 2, '.', ' ') }}</td>
                                <td>{{ number_format($item['accrual_this_month'] + 0, 2, '.', ' ') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d.m.Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray">Нет данных</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layout.markup')

@section('title')
    Свод по Заказчикам
@endsection

@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')
    {{--    ФИЛЬТР --}}
    <div class="mb-3">
        @include('Answer.custom_response')
        @include('Answer.validator_response')
        <div class="w-100 shadow border rounded p-3">
            <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i class="fa fa-search search-icon mr-2"></i>Поиск
            </div>
            <form action="" method="GET" class="check__field">
                @csrf
                <div class="row m-0" id="search">
                    <div class="w-100 row m-0 py-3">
                        @if (\App\Helpers\UserHelper::isManager())
                            <div class="form-group col-12 col-md-4 col-lg-3">
                                <label class="form-label">Менеджер</label>
                                <select class="form-control form-control-sm">
                                    <option>{{ auth()->user()->full_name }}</option>
                                </select>
                            </div>
                        @else
                            <div class="form-group col-12 col-md-4 col-lg-3">
                                <label for="" class="form-label">Менеджер </label>
                                <select class="form-control form-control-sm" name="manager_id">
                                    <option value="">Не выбрано</option>
                                    @foreach ($managers as $manager)
                                        <option @if ($manager['id'] == request()->manager_id) selected @endif
                                        value="{{ $manager['id'] }}">{{ $manager['full_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Долг</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control form-control-sm" name="duty_from"
                                       value="{{ request()->duty_from ?? '' }}" placeholder="От">
                                <input type="number" step="0.01" class="form-control form-control-sm" name="duty_to"
                                       value="{{ request()->duty_to ?? '' }}" placeholder="До">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Объем ЗБП </label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                       name="sum_without_space_from"
                                       value="{{ request()->sum_without_space_from ?? '' }}"
                                       placeholder="От">
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                       name="sum_without_space_to" value="{{ request()->sum_without_space_to ?? '' }}"
                                       placeholder="До">
                            </div>
                        </div>
                        @role('Администратор')
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Маржа</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control form-control-sm" name="profit_from"
                                       value="{{ request()->profit_from ?? '' }}" placeholder="От">
                                <input type="number" step="0.01" class="form-control form-control-sm" name="profit_to"
                                       value="{{ request()->profit_to ?? '' }}" placeholder="До">
                            </div>
                        </div>
                        @endrole
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Срок в работе (дни)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                       name="date_diff_from" value="{{ request()->date_diff_from ?? '' }}"
                                       placeholder="От">
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                       name="date_diff_to" value="{{ request()->date_diff_to ?? '' }}" placeholder="До">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Проект</label>
                            <select class="form-control border form-control-sm select-2" title="Пожалуйста, выберите"
                                    name="project_id">
                                <option value=" " selected>Не выбрано</option>
                                @foreach ($project as $project_info)
                                    <option @if ($project_info['id'] == request()->project_id) selected @endif
                                    value="{{ $project_info['id'] }}">{{ $project_info['project_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Заказчик</label>
                            <select class="form-control border form-control-sm select-2" title="Пожалуйста, выберите"
                                    name="client_id">
                                <option value="">Не выбрано</option>
                                @foreach ($clients as $client)
                                    <option @if ($client['id'] == request()->client_id) selected
                                            @endif value="{{ $client['id'] }}">
                                        {{ $client['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Дата</label>
                            <input class="form-control form-control-sm" type="month" name="month" required
                                   value="{{ request()->month ?? now()->format('Y-m') }}">
                        </div>

                        <div class="col-12 p-0">
                            <div class="form-group col-12">
                                <div class="w-100 d-flex justify-content-end">
                                    <button class="btn btn-sm btn-success">Искать</button>
                                </div>
                            </div>
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
            <div class="col-12 col-md-9">
                <div class="row">
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ number_format($statistics['finish_duty'], 2, '.', ' ') }}
                                    ₽</strong></div>
                            <div class="text-12 nowrap-dot">Общий долг:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ number_format($statistics['sum_without_space'], 2, '.', ' ') }}</strong>
                            </div>
                            <div class="text-12 nowrap-dot">Общий объем ЗБП:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ number_format($statistics['sum_gross_income'], 2, '.', ' ') }}</strong>
                            </div>
                            <div class="text-12 nowrap-dot">ВД:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ number_format($statistics['profit'], 2, '.', ' ') }}</strong>
                            </div>
                            <div class="text-12 nowrap-dot">Маржа:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ number_format($statistics['middle_check'], 2, '.', ' ') }}</strong>
                            </div>
                            <div class="text-12 nowrap-dot">Средний чек:</div>
                        </div>
                    </div>
                    {{--                        <div class="col-12 col-sm-6 col-xl-4 mb-2">--}}
                    {{--                            <div class="px-3 py-2 shadow border bg-white rounded">--}}
                    {{--                                <div class="text-24">--}}
                    {{--                                    <strong>{{ number_format($statistics['sum_symbols_in_day'], 2, '.', ' ') }}</strong>--}}
                    {{--                                </div>--}}
                    {{--                                <div class="text-12 nowrap-dot">Итого знаков:</div>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="w-100">
                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <div style="font-size: 40px">$</div>
                                <div class="pl-3"><span class="text-14">USD:</span></div>
                                <div
                                    class="pl-2 text-18">{{ $rates->where('id_currency', 2)->first()->rate ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 40px">€</div>
                            <div class="pl-3"><span class="text-14">EUR:</span></div>
                            <div class="pl-2 text-18">{{ $rates->where('id_currency', 3)->first()->rate ?? '' }}</div>
                        </div>
                    </div>

                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 38px">₴</div>
                            <div class="pl-3"><span class="text-14">UAH:</span></div>
                            <div class="pl-2 text-18">{{ $rates->where('id_currency', 4)->first()->rate ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole
    {{--    ТАБЛИЦА --}}
    <div class="w-100 shadow border rounded">
        <div class=>
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Общий свод по заказчикам</h4>
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
                            <th>Состояние</th>
                            <th class="fw-bold" style="min-width: 120px;">Долг</th>
                            <th>Проект</th>
                            <th>Заказчик</th>
                            <th style="min-width: 120px;">Объем ЗБП</th>
                            <th style="min-width: 120px;">ВД</th>
                            @role('Администратор')
                            <th style="min-width: 120px;">Маржа</th>
                            @endrole

                            <th>Менеджер</th>
                            <th>Сроки оплаты</th>
                            <th>Срок в работе</th>
                            <th style="min-width: 120px;">Цена проекта</th>
                            <th style="min-width: 120px;">Цена автора</th>
                            <th>Знаки в день</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($reports as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('client_project.show', ['project' => $item['id'], 'month' => request()->month ?? now()->format('Y-m')]) }}">
                                        <i class="fas fa-grip-horizontal"></i>
                                    </a>
                                </td>
                                <td>{{ $item['id'] }}</td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm"
                                            style="min-width: 170px; background-color: {{ $item['project_status_payment']['color'] ?? '#ffffff' }}70 "
                                            name="status_payment_id"
                                            onchange="editStatusPaymentProject(this, '{{ route('project.partial_update', ['id' => $item['id']]) }}')">
                                        <option value="">
                                            Не выбрано
                                        </option>
                                        @foreach ($statusPayments as $status)
                                            <option value="{{ $status['id'] }}"
                                                    @if ($status['id'] == ($item['projectStatusPayment']['id'] ?? 0)) selected @endif>
                                                {{ $status['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="fw-bolder">
                                        <span
                                            @if(($item['finish_duty'] + $item['duty'] + $item['remainder_duty']) < 0) class="text-danger" @endif>
                                            {{ number_format($item['finish_duty'] + $item['duty'] + $item['remainder_duty'] ?? '-', 2, '.', ' ') }}
                                        </span>
                                </td>
                                <td>{{ $item['project_name'] ?? '-' }}</td>
                                <td>
                                    @foreach ($item['projectClients'] as $client)
                                        {{ $client['name'] }}
                                    @endforeach
                                </td>
                                <td>{{ number_format($item['sum_without_space'] + 0 ?? '-', 2, '.', ' ') }}</td>
                                <td>{{ number_format($item['sum_gross_income'] + 0 ?? '-', 2, '.', ' ') }}</td>
                                @role('Администратор')
                                <td>{{ number_format($item['profit'] + 0 ?? '-', 2, '.', ' ') }}</td>
                                @endrole
                                <td>{!! $item['project_user']['full_name'] ?? '<span class="test-12 fst-italic text-gray">Пусто</span>' !!}</td>
                                <td>{{ $item['payment_terms'] ?? '-' }} </td>
                                <td>{{ $item['date_diff'] . ' дней' ?? '-' }}</td>
                                <td>{{ number_format($item['sum_price_client'] + 0 ?? '-', 2, '.', ' ') }}</td>
                                <td>{{ number_format($item['sum_price_author'] + 0 ?? '-', 2, '.', ' ') }}</td>
                                <td>{!! $item['symbol_in_day'] ?? '<span class="test-12 fst-italic text-gray">Пусто</span>' !!}</td>
                                <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d.m.Y') ?? '-' }}</td>
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
    <script src="{{ asset('js/project.js') }}"></script>
@endsection

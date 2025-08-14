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
                <div class="row m-0" id="search">
                    <div class="w-100 row m-0 py-3">

                        <div class="col-12 col-md-4 col-lg-3 mb-3">
                            <label class="form-label" for="">Дата</label>
                            <input class="form-control form-control-sm" type="month" name="month"
                                   value="{{ request()->month ?? now()->format('Y-m') }}">
                        </div>

                        <div class="col-12 p-0">
                            <div class="form-group col-12">
                                <div class="w-100 d-flex justify-content-end" style="gap: 10px">
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
            <div class="col-12 ">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong> {{ number_format($indicators['sum_amount'] + 0, 0, '.', ' ') }} ₽</strong>
                            </div>
                            <div class="text-12 nowrap-dot">Общая сумма договоров:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ number_format($indicators['sum_accrual_this_month'] + 0, 0, '.', ' ') }}
                                    ₽</strong></div>
                            <div class="text-12 nowrap-dot">Сумма начислений в месяце:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ number_format($indicators['sum_duty'] + 0, 0, '.', ' ') }}
                                    ₽</strong></div>
                            <div class="text-12 nowrap-dot">Общий долг:</div>
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
                            <th>Отдел</th>
                            <th>Проект</th>
                            <th>Долг</th>
                            <th>+ долг</th>

                            <th>Контрагент</th>
                            <th>Отчетная дата</th>

                            <th>М-ц работы</th>

                            <th>Сумма дог.</th>
                            <th>Начислено</th>

                            <th>Состояние</th>

                            <th>Спец. в проекте</th>
                            <th>Менеджер</th>
                            <th>Планы</th>
                            <th>Продвигаем сайт</th>
                            <th>Часы</th>
                            <th>Счет оплаты</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($reports as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('report_service.show', ['project_id' => $item['id'], 'month' => request()->month]) }}">
                                        <i class="fas fa-grip-horizontal"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($item->services as $i => $service)
                                            @if($i === 0 || $service->serviceType->id !== $item->services[$i-1]->serviceType->id)
                                                <div class="select-2-custom-state-color"
                                                     style="background-color: {{ $service->serviceType->color }}">
                                                    {{ $service->serviceType->name }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between flex-nowrap">
                                        <span> {{ $item['project_name'] }}</span>

                                        <a target="_blank" class="px-3 d-flex align-items-center text-primary"
                                           href="{{route('project.edit',['project'=> $item['id']])}}"><i
                                                class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </td>
                                <td class="nowrap"><strong>{{ number_format($item->duty, 2, '.', ' ')}}</strong></td>
                                <td>
                                    <input type="number"
                                           onchange="window.update(this, '{{ route('project.partial_update', ['id' => $item->id]) }}')"
                                           style="width: 80px"
                                           class="form-control form-control-sm"
                                           name="duty_on_services"
                                           value="{{ $item['duty_on_services'] + 0 }}"
                                    >
                                </td>
                                <td style="max-width: 180px;">{{ $item->legal_name_company }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['reporting_data'])->format('d.m.y') }}</td>
                                <td class="text-center">{{ $item->count_month_work }}</td>
                                <td>
                                    <input type="number"
                                           style="width: 80px"
                                           class="input_amount form-control form-control-sm"
                                           data-project-id="{{$item['id']}}"
                                           name="amount"
                                           value="{{ count($item['monthlyAccruals']) > 0 ?  $item['monthlyAccruals'][0]['amount'] + 0 : 0 }}"
                                    >
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between flex-nowrap">
                                        <span
                                            class="nowrap">{{ number_format($item->sum_accrual_this_month + 0, 2, '.', ' ') }}</span>

                                        <a target="_blank" class="px-3 d-flex align-items-center text-primary"
                                           href="{{ route('project-service.index', ['project_id' => $item['id']]) }}"><i
                                                class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm select2-with-color"
                                            name="status_id"
                                            onchange="window.update(this, '{{ route('project.partial_update', ['id' => $item->id]) }}')"
                                    >
                                        <option value="">Не выбрано</option>
                                        @foreach($statuses as $status)
                                            <option @if($item->status_id == $status->id) selected
                                                    @endif data-color="{{ $status->color }}"
                                                    value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    @if($item['leadingSpecialist'])
                                        <span class="select-2-custom-state-color"
                                              style="background-color: {{ $item['leadingSpecialist']['color'] ?? '' }}">
                                            {{ $item['leadingSpecialist']['name'] ?? '-' }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->projectUser?->minName ?? '-' }}</td>
                                <td>
                                    @if($item['passport_to_work_plan'])
                                        <a href="{{ $item['passport_to_work_plan'] }}" target="_blank"
                                           class="text-primary">Перейти</a>
                                    @endif
                                </td>
                                <td>{{ $item['promoting_website'] }}</td>
                                <td class="text-center">{{ $item['hours'] + 0 }}</td>
                                <td>{{ $item->requisite?->name ?? '-' }}</td>
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
    <script src="{{ asset('js/service.js') }}?v=@version"></script>

    <script>

        $('.input_amount').change(function () {
            let amount = $(this).val();

            let payload = {
                date: '{{ request()->month ?? now()->format('Y-m') }}',
                amount: amount !== '' ? amount : 0,
                project_id: $(this).data('project-id')
            }

            if (amount === '') $(this).val(0)

            ajax('post', '{{ route('monthly_accrual.update') }}', payload)
        })

        window.update = function (el, url) {
            if ($(el).attr('name') === 'duty_on_services' && $(el).val() === '') {
                $(el).val(0)
            }
            ajax('post', url, {[$(el).attr('name')]: $(el).val()})
        }

    </script>
@endsection

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
                                   value="{{ request()->month ?? "" }}">
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
                            <th>Контрагент</th>
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
                                    <a href="{{ route('report_service.show', ['project_id' => $item['id'], 'month' => request()->month]) }}">
                                        <i class="fas fa-grip-horizontal"></i>
                                    </a>
                                </td>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['project_name'] }}</td>
                                <td>{{ $item['legal_name_company'] }}</td>
                                <td>{{ $item['project_theme_service'] }}</td>
                                <td>{{ $item['reporting_data'] }}</td>

                                <td class="text-center">{{ $item->count_month_work }}</td>
                                <td>{{ $item['terms_payment'] }}</td>
                                <td>{{ $item['total_amount_agreement'] + 0 }}</td>
                                <td>
                                    <input type="number"
                                           class="input_amount form-control form-control-sm"
                                           data-project-id="{{$item['id']}}"
                                           name="amount"
                                           value="{{ count($item['monthlyAccruals']) > 0 ?  $item['monthlyAccruals'][0]['amount'] + 0 : 0 }}"
                                    >
                                </td>
                                <td>
                                    @if($item['leadingSpecialist'])
                                        <span class="select-2-custom-state-color" style="background-color: {{ $item['leadingSpecialist']['color'] ?? '' }}">
                                            {{ $item['leadingSpecialist']['name'] ?? '-' }}
                                        </span>
                                    @else - @endif
                                </td>
                                <td>{{ $item->projectUser?->minName ?? '-' }}</td>
                                <td>{{ $item['region'] }}</td>
                                <td style="max-width: 300px; word-break: break-word;">{{ $item['passport_to_work_plan'] }}</td>
                                <td class="text-center">{{ $item['hours'] + 0 }}</td>
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

    <script>

        $('.input_amount').change(function(){
            let date = '{{ request()->month ?? now()->format('Y-m') }}'
            let amount = $(this).val();
            let projectId = $(this).data('project-id');

            if(amount !== ''){
                ajax('post', {data: date, project_id: projectId, amount: amount})
            }
        })

        window.ajax = function (method, params) {
            if (window.ajaxStatus) {
                window.ajaxStatus = false;
                $.ajax({
                    url: '{{ route('monthly_accrual.update') }}',
                    method: method,
                    data: params,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                }).done((res) => {
                    showNotification('success', 'Данные успешно обновлены.')
                    console.log(res)
                    window.ajaxStatus = true;
                }).fail((error) => {
                    showNotification('error', 'Произошла ошибка запроса.')
                    console.log(error)
                    window.ajaxStatus = true;
                })
            } else {
                alert('Дождитесь завершения запроса');
            }
        }

        window.showNotification = function (status, message) {

            let alertSuccess = $('.ajax-success');
            let alertError = $('.ajax-error');

            alertSuccess.hide();
            alertError.hide();

            switch (status) {
                case 'success' :
                    alertSuccess.text(message).show();
                    window.saveAudio.play();
                    break;
                case 'error' :
                    alertError.text(message).show();
                    break;
            }

            setTimeout(() => {
                alertSuccess.hide();
                alertError.hide();
            }, 4000);
        }
    </script>
@endsection

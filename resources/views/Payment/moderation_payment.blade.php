@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div>
        <div>
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        <div class="w-100">
            {{--    ФИЛЬТР --}}
            <div class="mb-3">
                <div class="w-100 shadow border rounded p-3">
                    <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                            class="fa fa-search search-icon mr-2"></i>Поиск
                    </div>

                    <form action="" class="check__field">
                        @csrf
                        <div class="row m-0" id="search">

                            <div class="form-group col-12 col-md-4 col-lg-3">
                                <label for="" class="form-label">Проект</label>
                                <select class="form-control border form-control-sm select-2"
                                        title="Пожалуйста, выберите"
                                        name="project_id">
                                    <option value=" " selected>Не выбрано</option>
                                    @foreach ($projects as $project_info)
                                        <option @if ($project_info['id'] == request()->project_id) selected @endif
                                        value="{{ $project_info['id'] }}">{{ $project_info['project_name']}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-4 col-lg-3">
                                <label for="" class="form-label">Счёт</label>
                                <select class="form-select form-select-sm" name="invoice">
                                    <option value="">Не выбрано</option>
                                    <option value="sber_a">Сбер А</option>
                                    <option value="tinkoff_a">Тинькофф А</option>
                                    <option value="tinkoff_k">Тинькофф K</option>
                                    <option value="sber_d">Сбер Д</option>
                                    <option value="sber_k">Сбер К</option>
                                    <option value="privat">Приват</option>
                                    <option value="um">ЮМ</option>
                                    <option value="wmz">Сбер КА</option>
                                    <option value="birja">Биржи</option>
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-4 col-lg-3">
                                <label class="form-label">Дата</label>
                                <div class="input-group">
                                    <input type="date" name="date" class="form-control form-control-sm"
                                           value="{{ request()->date ?? null}}">
                                </div>
                            </div>

                            <div class="form-group col-12 col-md-4 col-lg-3">
                                <label for="" class="form-label">Метка оплаты</label>
                                <select class="form-select form-select-sm" name="is_mark_payment">
                                    <option value="">Не выбрано</option>
                                    <option value="1">С меткой</option>
                                    <option value="0">Без метки</option>
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-4 col-lg-3">
                                <label for="" class="form-label">Списание</label>
                                <select class="form-select form-select-sm" name="is_mark_back_duty">
                                    <option value="">Не выбрано</option>
                                    <option value="1">С меткой</option>
                                    <option value="0">Без метки</option>
                                </select>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-success">Искать</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mb-2">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-xl-4 mb-2">
                                <div class="px-3 py-2 shadow border bg-white rounded">
                                    <div class="text-24">
                                        <strong> {{ $paymentInfo['count_payment'] }} </strong>
                                    </div>
                                    <div class="text-12 nowrap-dot">Непроверенные оплаты:</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-4 mb-2">
                                <div class="px-3 py-2 shadow border bg-white rounded">
                                    <div class="text-24">
                                        <strong>{{$paymentInfo['sum_payment']}}</strong>
                                    </div>
                                    <div class="text-12 nowrap-dot">Сумма непроверенных оплат:</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-4 mb-2">
                                <div class="px-3 py-2 shadow border bg-white rounded">
                                    <div class="text-24">
                                        <strong>{{$paymentInfoBackDuty['sum_back_duty']}}</strong>
                                    </div>
                                    <div class="text-12 nowrap-dot">Сумма списаний:</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card shadow border bg-white rounded">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title ">Бухгалтерский учет</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="w-100 d-flex justify-content-center mb-3">
                        {{ $paymentList->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                    <div class="table-responsive">
                        <table style="padding: 0 3px 0 12px !important;" id="basic-datatables"
                               class="table-cut display table table-head-bg-info table-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Состояние</th>
                                <th style="font-size:8px!important; min-width: 10px; !important; padding: 0 3px 0 12px !important;">
                                    Метка оплаты
                                </th>
                                <th style="font-size:8px!important; min-width: 10px; !important; padding: 0 3px 0 12px !important;">
                                    Списание
                                </th>
                                <th>Дата</th>
                                <th>Проект</th>
                                <th></th>
                                <th></th>
                                <th style="min-width: 120px;">Сбер А</th>
                                <th style="min-width: 120px;">Тинькофф А</th>
                                <th style="min-width: 120px;">Тинькофф K</th>
                                <th style="min-width: 120px;">Сбер Д</th>
                                <th style="min-width: 120px;">Сбер К</th>
                                <th style="min-width: 120px;">Приват</th>
                                <th style="min-width: 120px;">ЮМ</th>
                                <th style="min-width: 120px;">Сбер КА</th>
                                <th style="min-width: 120px;">Биржи</th>

                                <th>Комментарий</th>

                            </tr>


                            </thead>
                            <tbody>
                            @foreach($paymentList as $payment)
                                <tr style="font-size: 10px;"
                                    class="row_{{ $payment['id'] }}"
                                    data-url="{{ route('payment.update', ['id' => $payment['id']]) }}">
                                    <td>
                                        {{ $payment['id'] }}
                                    </td>
                                    <td>
                                        <div>
                                            <select style="background-color: {{ $payment['status']['color'] }}70"
                                                    class="form-select form-select-sm" name="status_payment_id"
                                                    disabled>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status['id'] }}"

                                                            @if($payment['status_payment_id'] === $status['id']) selected
                                                            @endif
                                                            value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="checkbox" name="mark" @if((bool)$payment['mark']) checked
                                                   @endif disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="checkbox" name="back_duty"
                                                   @if((bool)$payment['back_duty']) checked @endif disabled>
                                        </div>
                                    </td>
                                    <td class="nowrap">
                                        {{ $payment['date'] }}
                                    </td>
                                    <td>
                                        {{ $payment['project']['project_name'] ?? ''}}
                                    </td>
                                    <td>
                                        <div class="btn btn-sm btn-primary edit"
                                             onclick="edit('row_{{ $payment['id'] }}')">
                                            <i class="fas fa-pen"></i>
                                        </div>
                                        <div class="btn btn-sm btn-success save" style="display: none;"
                                             onclick="save('row_{{ $payment['id'] }}', true)">
                                            <i class="fas fa-save"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group col-12 d-flex justify-content-between destroy">
                                            <a href="{{route('payment.delete', ['id' => $payment['id']])}}"
                                               class="btn btn-sm btn-outline-danger" onclick="confirmDelete()"><i
                                                    class="fas fa-minus"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="sber_a" class="form-control form-control-sm"
                                                   value="{{ $payment['sber_a'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="tinkoff_a" class="form-control form-control-sm"
                                                   value="{{ $payment['tinkoff_a'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="tinkoff_k" class="form-control form-control-sm"
                                                   value="{{ $payment['tinkoff_k'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="sber_d" class="form-control form-control-sm"
                                                   value="{{ $payment['sber_d'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="sber_k" class="form-control form-control-sm"
                                                   value="{{ $payment['sber_k'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="privat" class="form-control form-control-sm"
                                                   value="{{ $payment['privat'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="um" class="form-control form-control-sm"
                                                   value="{{ $payment['um'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="wmz" class="form-control form-control-sm"
                                                   value="{{ $payment['wmz'] }}" disabled>
                                        </div>
                                    </td>

                                    <td>
                                        <div>
                                            <input type="number" name="birja" class="form-control form-control-sm"
                                                   value="{{ $payment['birja'] }}" disabled>
                                        </div>
                                    </td>



                                    <td>
                                        <div>
                                            <textarea class="form-control form-control-sm" name="comment" id=""
                                                      cols="30" rows="2" disabled>{{ $payment['comment'] }}</textarea>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100 d-flex justify-content-center">
                        {{ $paymentList->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom_js')
    <script
        src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script src="{{asset('js/payment.js')}}"></script>
    <script>
        window.confirmDelete = function () {
            var res = confirm('Вы действительно хотите удалить этот проект?')
            if (!res) {
                event.preventDefault();
            }
        }
    </script>
@endsection

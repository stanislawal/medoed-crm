@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    @include('Window.Payment.create')
    <div>
        <div>
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        <div class="w-100">
            <div class="card shadow border bg-white rounded">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Заявки оплаты</h4>
                        <div class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#create_payment">
                            Создать заявку
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables"
                               class="display table table-head-bg-info table-center">
                            <thead>
                            <tr>
                                <th>Состояние</th>
                                <th>Дата</th>
                                <th>Сбер Д</th>
                                <th>Сбер К</th>
                                <th>Приват</th>
                                <th>ЮМ</th>
                                <th>ВМЗ</th>
                                <th>Биржи</th>
                                <th>Проект</th>
                                <th>Комментарий</th>
                                <th>Удалить</th>
                                <th>Изменить</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($paymentList as $payment)
                                <tr style="background-color: {{ $payment['status']['color'] }}70"
                                    class="row_{{ $payment['id'] }}"
                                    data-url="{{ route('payment.update', ['id' => $payment['id']]) }}">
                                    <td>
                                        <div>
                                            <select class="form-select form-select-sm" name="status_payment_id"
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
                                    <td class="nowrap">
                                        {{ $payment['date'] }}
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
                                        {{ $payment['project']['project_name'] ?? ''}}
                                    </td>

                                    <td>
                                        <div>
                                            <textarea class="form-control form-control-sm" name="comment" id=""
                                                      cols="30" rows="2" disabled>{{ $payment['comment'] }}</textarea>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @if((bool)!$payment['mark'])
                                            <div class="form-group col-12 d-flex justify-content-between destroy">
                                                <a href="{{route('payment.delete', ['id' => $payment['id']])}}"
                                                   class="btn btn-sm btn-outline-danger" onclick="confirmDelete()"><i
                                                        class="fas fa-minus"></i></a>
                                            </div>
                                        @else
                                            <span class="text-12 font-weight-bold">Недоступно</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if((bool)!$payment['mark'])
                                            <div class="btn btn-sm btn-primary edit"
                                                 onclick="edit('row_{{ $payment['id'] }}')">
                                                <i class="fas fa-pen"></i>
                                            </div>
                                            <div class="btn btn-sm btn-success save" style="display: none;"
                                                 onclick="save('row_{{ $payment['id'] }}', true)">
                                                <i class="fas fa-save"></i>
                                            </div>
                                        @else
                                            <span class="text-12 font-weight-bold">Недоступно</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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

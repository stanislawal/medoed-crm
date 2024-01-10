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
                            <select class="form-control border form-control-sm select-2" title="Пожалуйста, выберите"
                                    name="project_id">
                                <option value=" " selected>Не выбрано</option>
                                @foreach ($projects as $project_info)
                                    <option @if ($project_info['id'] == request()->project_id) selected @endif
                                    value="{{ $project_info['id'] }}">{{ $project_info['project_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div  class="form-group col-12 col-md-4 col-lg-3">
                            <label for="" class="form-label">Счёт</label>
                            <select class="form-select form-select-sm" name="invoice">
                                <option value="">Не выбрано</option>
                                <option value="sber_a">Сбер А</option>
                                <option value="tinkoff_a">Тинькофф А</option>
                                <option value="tinkoff_k">Тинькофф K</option>
                                <option value="sber_d">Тинькофф Д</option>
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
                        <div>
                            <button class="btn btn-sm btn-success">Искать</button>
                        </div>
                    </div>
                </form>
            </div>
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
                    <div class="w-100 d-flex justify-content-center mb-3">
                        {{ $paymentList->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                    <div class="table-responsive">
                        <table id="basic-datatables"
                               class="display table table-head-bg-info table-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Состояние</th>
                                <th>Дата</th>
                                <th>Проект</th>
                                <th>Удалить</th>
                                <th>Изменить</th>
                                <th>Сбер А</th>
                                <th>Тинькофф А</th>
                                <th>Тинькофф К</th>
                                <th>Тиньеофф Д</th>
                                <th>Сбер К</th>
                                <th>Приват</th>
                                <th>ЮМ</th>
                                <th>Сбер КА</th>
                                <th>Биржи</th>

                                <th>Комментарий</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paymentList as $payment)
                                <tr
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
                                    <td class="nowrap">
                                        {{ $payment['date'] }}
                                    </td>
                                    <td>
                                        {{ $payment['project']['project_name'] ?? ''}}
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
                                    <td>
                                        <div>
                                            <input type="number" name="sber_a" class="min-input"
                                                   value="{{ $payment['sber_a'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="tinkoff_a" class="min-input"
                                                   value="{{ $payment['tinkoff_a'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="tinkoff_k" class="min-input"
                                                   value="{{ $payment['tinkoff_k'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="sber_d" class="min-input"
                                                   value="{{ $payment['sber_d'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="sber_k" class="min-input"
                                                   value="{{ $payment['sber_k'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="privat" class="min-input"
                                                   value="{{ $payment['privat'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="um" class="min-input"
                                                   value="{{ $payment['um'] }}" disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="number" name="wmz" class="min-input"
                                                   value="{{ $payment['wmz'] }}" disabled>
                                        </div>
                                    </td>

                                    <td>
                                        <div>
                                            <input type="number" name="birja" class="min-input"
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
        $(document).ready(function () {
            $("#select2insidemodal").select2({
                dropdownParent: $("#create_payment")
            });
        });

        window.confirmDelete = function () {
            var res = confirm('Вы действительно хотите удалить этот проект?')
            if (!res) {
                event.preventDefault();
            }
        }
    </script>
@endsection

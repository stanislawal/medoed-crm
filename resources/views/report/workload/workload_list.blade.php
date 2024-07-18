@extends('layout.markup')

@section('title')
    Объем работы
@endsection

@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
        .table td, .table th {
            height: 50px !important;
            font-weight: 600;
            padding: 0 15px !important;
        }

        .table-head-bg-info thead {
            border: none !important;
        }

        .table-head-bg-info thead th{
            border-right: 1px solid #f2f2f2!important;
        }

        .table-head-bg-info thead th:last-child{
            border-right: none!important;
        }

        tbody{
            border-bottom: 1px solid #46464624!important;
            border-right: 1px solid #46464624!important;
        }

    </style>
@endsection

@section('content')

    <div class="mb-3">
        @include('Answer.custom_response')
        @include('Answer.validator_response')
        <div class="w-100 shadow border rounded p-3">
            <form action="" class="check__field">
                <div class="row">

                    @if(\App\Helpers\UserHelper::isManager())
                        <div class="form-group col-12 col-sm-6 col-lg-4">
                            <label class="form-label">Менеджер</label>
                            <select class="form-control form-control-sm">
                                <option>{{ \App\Helpers\UserHelper::getUser()->full_name }}</option>
                            </select>
                        </div>
                    @else
                        <div class="form-group col-12 col-md-6 col-lg-4">
                            <label for="" class="form-label">Менеджер</label>
                            <select class="form-control form-control-sm" name="manager_id">
                                <option value="">Не выбрано</option>
                                @foreach ($managers as $manager)
                                    <option @if($manager['id'] == request()->manager_id) selected
                                            @endif value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group col-12 col-sm-6 col-lg-4">
                        <label class="form-label">Период</label>
                        <div class="input-group">
                            <input type="date" name="date_from" class="form-control form-control-sm"
                                   value="{{ request()->date_from ?? \Carbon\Carbon::parse(now())->startOfMonth()->format('Y-m-d') }}"
                                   required>
                            <input type="date" name="date_before" class="form-control form-control-sm"
                                   value="{{ request()->date_before ?? \Carbon\Carbon::parse(now())->endOfMonth()->format('Y-m-d') }}"
                                   required>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <button class="btn btn-sm btn-success">Загрузить</button>
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
                                <strong>{{ $report['without_space'] }}</strong></div>
                            <div class="text-12 nowrap-dot">Общий объем збп:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ $report['gross_income'] }}</strong></div>
                            <div class="text-12 nowrap-dot">Валовый доход (сумма):</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--ТАБЛИЦА--}}
    <div class="w-100 shadow border rounded">
        <div class=>
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Объемы работы</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables"
                           class="display table table-head-bg-info table-striped table-center table-cut">
                        <thead>
                        <tr>
                            @foreach($report['headers'] as $key => $header)
                                <th class="text-center" @if($key != 0) colspan="2" @endif >{{ $header }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($report['data'] as $item)
                            <tr>
                                @foreach($item as $el)
                                    <td class="nowrap text-center">{{ $el }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
@endsection

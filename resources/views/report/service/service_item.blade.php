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
                            <div class="text-24"><strong>{{ $project->project_name }}</strong></div>
                            <div class="text-12 nowrap-dot">Проект:</div>
                        </div>
                    </div>

{{--                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">--}}
{{--                        <div class="px-3 py-2 shadow border bg-white rounded">--}}
{{--                            <div class="text-24"><strong>---</strong></div>--}}
{{--                            <div class="text-12 nowrap-dot">Значение:</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">--}}
{{--                        <div class="px-3 py-2 shadow border bg-white rounded">--}}
{{--                            <div class="text-24"><strong>---</strong></div>--}}
{{--                            <div class="text-12 nowrap-dot">Значение:</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">--}}
{{--                        <div class="px-3 py-2 shadow border bg-white rounded">--}}
{{--                            <div class="text-24"><strong>---</strong></div>--}}
{{--                            <div class="text-12 nowrap-dot">Значение:</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
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
                            <th>ID</th>
                            <th>Отдел</th>
                            <th>Услуга</th>
                            <th>Специалисты</th>
                            <th>Дата создания</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($reports as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td><span class="select-2-custom-state-color" style="background-color: {{ $item->serviceType->color }}">{{ $item->serviceType->name }}</span></td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @foreach($item->specialists as $specialist)
                                        <span class="select-2-custom-state-color" style="background-color: {{$specialist->color}}">{{ $specialist->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d.m.Y') }}</td>
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
@endsection

@extends('layout.markup')
@section('title', 'База лидов')
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
            <div class="card shadow border bg-white rounded mb-0">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Услуги</h4>
                        <div class="d-flex align-items-center">
                            <div class="me-3">Всего записей: <strong>{{ $projectServices->total() }}</strong></div>
                            <div class="btn btn-sm btn-success" data-bs-toggle="modal"
                                 data-bs-target="#create_project_service">
                                Создать услугу
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="w-100 d-flex justify-content-center mb-3">
                        {{ $projectServices->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                    <div class="table-responsive" style="height: calc(100vh - 297px);">
                        <table id="basic-datatables" class="display table table-head-bg-info table-center table-cut">
                            <thead>
                            <tr>
                                <th style="min-width: 40px; text-align: center">ID</th>
                                <th style="min-width: 180px">Проект</th>
                                <th style="min-width: 100px">Вид услуги</th>
                                <th style="min-width: 100px">Тема проекта</th>
                                <th style="min-width: 100px">Отчетная дата</th>
                                <th style="min-width: 100px">Условия оплаты</th>
                                <th style="min-width: 170px">Специалисты</th>
                                <th style="min-width: 100px">Регион продвижения</th>
                                <th style="min-width: 100px">Общая сумма договора</th>
                                <th style="min-width: 100px">Начислено в этом месяце</th>
                                <th style="min-width: 165px">Задача</th>
                                <th style="min-width: 100px">Ссылка на план работы</th>
                                <th>Дата создания</th>
                                <th>Удалить</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projectServices as $item)
                                <tr data-url="{{ route('project-service.update', ['project_service' => $item->id ]) }}">
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td>
                                        <select class="form-select form-select-sm select-2" name="project_id">
                                            <option value="">Не выбрано</option>
                                            @foreach($projects as $project)
                                                <option @if($project->id === $item->project->id) selected @endif
                                                    value="{{ $project->id }}">{{ $project->project_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" name="service_type_id">
                                            <option value="">Не выбрано</option>
                                            @foreach($service_type as $type)
                                                <option @if($type->id === $item->serviceType->id) selected @endif value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input class="form-control form-control-sm" type="text" name="project_theme" value="{{ $item->project_theme ?? '' }}"></td>
                                    <td class="text-center">
                                        <input class="form-control form-control-sm" type="date" name="reporting_data" value="{{ $item->reporting_data ?? '' }}">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" type="text" name="terms_payment" value="{{ $item->terms_payment ?? ''}}">
                                    </td>
                                    <td>

                                        <select class="form-select form-select-sm select-2" multiple name="specialist_service_id[]"
                                                required>
                                            <option value="">Не выбрано</option>
                                            @foreach($specialists as $specialist)
                                                <option @if(in_array($specialist->id, $item->specialists->pluck('id')->toArray())) selected @endif value="{{ $specialist->id }}">{{ $specialist->name }}</option>
                                            @endforeach
                                        </select>

                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" type="text" name="region" value="{{ $item->region ?? '' }}">
                                    </td>
                                    <td class="text-center">
                                        <input class="form-control form-control-sm" type="number" name="all_price" value="{{ $item->all_price }}">
                                    </td>
                                    <td class="text-center">
                                        <input class="form-control form-control-sm" type="number" name="accrual_this_month" value="{{ $item->accrual_this_month }}">
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select form-select-sm" name="task" required>
                                            <option value="">Не выбрано</option>
                                            <option @if($item->task === 'Разовая') selected @endif>Разовая</option>
                                            <option @if($item->task === 'Сопровождение') selected @endif>Сопровождение</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" type="text" name="link_to_work_plan" value="{{ $item->link_to_work_plan ?? '' }}">
                                    </td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d.m.Y') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('project-service.destroy', ['project_service' => $item->id ]) }}"
                                              method="post">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="window.confirmDelete()">
                                                <i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100 d-flex justify-content-center mt-3">
                        {{ $projectServices->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Window.ProjectService.create')

@endsection
@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script src="{{asset('js/project_service.js')}}"></script>

    <script>
        $(document).on('shown.bs.modal', '.modal', function () {
            console.log('open')
            $(this).find('.select2-search__field').focus();
        });

        window.confirmDelete = function () {
            var res = confirm('Вы действительно хотите удалить эту услугу?')
            if (!res) {
                event.preventDefault();
            }
        }
    </script>
@endsection

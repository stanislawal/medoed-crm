@extends('layout.markup')
@section('title', 'База услуг')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div>
        <div>
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        <div class="mb-3">
            <div class="w-100 shadow border rounded p-3 bg-white">

                <form action="" class="check__field">
                    @csrf
                    <div class="row m-0">
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">ID</label>
                            <div class="input-group">
                                <input type="number" name="id" class="form-control form-control-sm"
                                       value="{{ request()->id ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Задача</label>
                            <select class="form-select form-select-sm" name="task">
                                <option value="">Не выбрано</option>
                                <option @if(request()->task === 'Разовая') selected @endif value="Разовая">Разовая</option>
                                <option @if(request()->task === 'Сопровождение') selected @endif value="Сопровождение">Сопровождение
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Создал</label>
                            <select class="form-select form-select-sm" name="user_id">
                                <option value="">Не выбрано</option>
                                @foreach($creater as $item)
                                    <option @if($item->id == request()->user_id) selected @endif
                                    value="{{ $item->id }}">{{ $item->minName }}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Проект</label>
                            <select class="form-select form-select-sm select-2" name="project_id">
                                <option value="">Не выбрано</option>
                                @foreach($projects as $project)
                                    <option @if($project->id == request()->project_id) selected @endif
                                    value="{{ $project->id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Отчетная дата</label>
                            <div class="input-group">
                                <input type="date" name="reporting_data" class="form-control form-control-sm"
                                       value="{{ request()->reporting_data ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Отдел</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color"
                                        name="service_type_id">
                                    <option value="">Не выбрано</option>
                                    @foreach($service_type as $type)
                                        <option @if($type->id == request()->service_type_id) selected
                                                @endif value="{{ $type->id }}"
                                                data-color="{{$type->color}}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Диапазон даты создания</label>
                            <div class="input-group">
                                <input type="date" name="date_from" class="form-control form-control-sm"
                                       value="{{ request()->date_from ?? "" }}">
                                <input type="date" name="date_before" class="form-control form-control-sm"
                                       value="{{ request()->date_before ?? "" }}">
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        @if(count(request()->all()) > 0)
                            <a href="{{ route('project-service.index') }}" class="btn btn-sm btn-danger me-3">Сбросить все</a>
                        @endif
                        <button class="btn btn-sm btn-success">Искать</button>
                    </div>
                </form>
            </div>
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
                                Добавить услугу
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
                                <th style="min-width: 100px">Отдел</th>
                                <th style="min-width: 100px">Тема проекта</th>
                                <th style="min-width: 200px">Услуга</th>
                                <th style="min-width: 100px">Отчетная дата</th>
                                <th style="min-width: 100px">Условия оплаты</th>
                                <th style="min-width: 150px">Специалисты</th>
                                <th style="min-width: 100px">Регион продвижения</th>
                                <th style="min-width: 100px">Общая сумма договора</th>
                                <th style="min-width: 100px">Начислено в этом месяце</th>
                                <th style="min-width: 165px">Задача</th>
                                <th style="min-width: 100px">Ссылка на план работы</th>
                                <th>Создал</th>
                                <th>Дата создания</th>
                                <th>Удалить</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projectServices as $item)
                                <tr data-url="{{ route('project-service.update', ['project_service' => $item->id ]) }}">
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td>
                                        <div class="d-flex flex-nowrap">
                                            <select class="form-select form-select-sm select-2" name="project_id">
                                                <option value="">Не выбрано</option>
                                                @foreach($projects as $project)
                                                    <option @if($project->id === $item->project->id) selected @endif
                                                    value="{{ $project->id }}">{{ $project->project_name }}</option>
                                                @endforeach
                                            </select>

                                            @if(!empty($item->project->id))
                                                <a target="_blank" class="px-3 d-flex align-items-center text-primary"
                                                   href="{{route('project.edit',['project'=> $item->project->id])}}"><i
                                                        class="fas fa-external-link-alt"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm select2-with-color"
                                                name="service_type_id">
                                            @foreach($service_type as $type)
                                                <option @if($type->id === $item->serviceType->id) selected
                                                        @endif value="{{ $type->id }}"
                                                        data-color="{{$type->color}}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{ $item->project->project_theme_service ?? '' }}
                                    </td>
                                    <td>
                                        <textarea style="width: 100%; resize: vertical!important; padding: 2px 5px!important;"
                                                  cols="2" type="text"
                                                  name="name"
                                                  class="form-control form-control-sm"
                                        >{{ $item->name }}</textarea>
                                    </td>
                                    <td class="text-center">
                                        {{ $item->project->reporting_data ?? '' }}
                                    </td>
                                    <td>
                                        {{ $item->project->terms_payment ?? ''}}
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm select2-with-color" multiple
                                                name="specialist_service_id[]"
                                                required>
                                            @foreach($specialists as $specialist)
                                                <option
                                                    data-color="{{ $specialist->color }}"
                                                    @if(in_array($specialist->id, $item->specialists->pluck('id')->toArray())) selected
                                                    @endif value="{{ $specialist->id }}">{{ $specialist->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{ $item->project->region ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        <input class="form-control form-control-sm" type="number" name="all_price"
                                               value="{{ $item->all_price }}">
                                    </td>
                                    <td class="text-center">
                                        <input class="form-control form-control-sm" type="number"
                                               name="accrual_this_month" value="{{ $item->accrual_this_month }}">
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select form-select-sm" name="task" required>
                                            <option value="">Не выбрано</option>
                                            <option @if($item->task === 'Разовая') selected @endif>Разовая</option>
                                            <option @if($item->task === 'Сопровождение') selected @endif>Сопровождение
                                            </option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        @if($item->project->passport_to_work_plan)
                                            <a href="{{ $item->project->passport_to_work_plan }}" target="_blank"
                                               class="text-primary">Перейти</a>
                                        @endif
                                    </td>
                                    <td class="nowrap">{{ $item->createdUser->minName ?? '' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d.m.Y') }}</td>
                                    <td class="text-center">
                                        <form
                                            action="{{ route('project-service.destroy', ['project_service' => $item->id ]) }}"
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
    <script src="{{asset('js/select2.js')}}?v=@version"></script>
    <script src="{{asset('js/project_service.js')}}?v=@version"></script>

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

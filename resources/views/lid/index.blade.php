@extends('layout.markup')
@section('title', 'База лидов')
@section('custom_css')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        .textarea-table {
            border: 1px solid #ced4da;
            border-radius: 3px;
        }
        .table td, .table th {
            height: 50px !important;
            font-weight: 600;
            padding: 0 15px !important;
        }
        .table-head-bg-info thead {
            border: none !important;
        }
        .table-head-bg-info thead th {
            border-right: 1px solid #f2f2f2 !important;
        }
        .table-head-bg-info thead th:last-child {
            border-right: none !important;
        }
        .table-head-bg-info tbody td:last-child {
            border-right: none !important;
        }
        tbody {
            border-bottom: 1px solid #46464624 !important;
        }
        thead tr {
            border-color: #1a202c;
        }
        thead tr:first-child {
            position: sticky !important;
            top: 0;
            z-index: 999999999;
        }

        thead tr:last-child {
            position: sticky !important;
            top: 0;
        }
        .table-fixed {
            position: sticky;
            left: 0;
            z-index: 1; /* Убедитесь, что фиксированные ячейки находятся выше остальных */
            background-color: #ffffff;
        }
        .table-fixed.sticked{
            background-color: #48abf7 !important;
            color: #ffffff;
            transition: 0.2s linear;
        }
        tbody tr:nth-child(even) > td {
            background-color: #f2f2f2 !important; /* Цвет для четных строк */
        }
        tbody tr:nth-child(odd) > td {
            background-color: #ffffff !important; /* Цвет для нечетных строк */
        }
        tbody tr.interesting > td {
            background-color: #31ce3630 !important;
        }
    </style>
@endsection
@section('content')
    <div>
        <div>
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        {{--    ФИЛЬТР --}}
        <div class="mb-3">
            <div class="w-100 shadow border rounded p-3 bg-white">

                <form action="" class="check__field">
                    @csrf
                    <div class="row m-0">
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Месяц</label>
                            <div class="input-group">
                                <input type="month" name="month" class="form-control form-control-sm"
                                       value="{{ request()->month }}">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Рек. комп.</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm" name="advertising_company">
                                    <option value="">Все</option>
                                    @foreach($advertisingCompany as $item)
                                        <option
                                            value="{{ $item }}" {{ request()->advertising_company == $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Имя/Ссылка</label>
                            <div class="input-group">
                                <input type="text" name="name_link" class="form-control form-control-sm"
                                       value="{{ request()->name_link ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Статус</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="lid_status_id[]">
                                    <option value="">Все</option>
                                    @foreach($lidStatuses as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->lid_status_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <div class="btn btn-sm btn-secondary" onclick="searchToggle()">Расширенный поиск</div>
                        </div>
                    </div>
                    <div class="row m-0" id="search" style="display: none">

                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Диапазон добавления</label>
                            <div class="input-group">
                                <input type="date" name="date_from" class="form-control form-control-sm"
                                       value="{{ request()->date_from ?? '' }}">
                                <input type="date" name="date_before" class="form-control form-control-sm"
                                       value="{{ request()->date_before ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Исключить статусы</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="without_lid_status_id[]">
                                    <option value="">Все</option>
                                    @foreach($lidStatuses as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->without_lid_status_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Ресурсы</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="resource_id[]">
                                    <option value="">Все</option>
                                    @foreach($resources as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->resource_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Сервисы</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="service_id[]">
                                    <option value="">Все</option>
                                    @foreach($services as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->service_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Аудит</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="audit_id[]">
                                    <option value="">Все</option>
                                    @foreach($audits as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->audit_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Статус специалиста</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="lid_specialist_status_id[]">
                                    <option value="">Все</option>
                                    @foreach($lidStatuses as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->lid_specialist_status_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Исключить статусы специалиста</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="without_lid_specialist_status_id[]">
                                    <option value="">Все</option>
                                    @foreach($lidStatuses as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->without_lid_specialist_status_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Созвон</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="call_up_id[]">
                                    <option value="">Все</option>
                                    @foreach($callUps as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->call_up_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Задача специалиста</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm select2-with-color" multiple
                                        name="specialist_task_id[]">
                                    <option value="">Все</option>
                                    @foreach($specialistTasks as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, request()->specialist_task_id ?? []) ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Специалист</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm" name="specialist_user_id">
                                    <option value="">Все</option>
                                    @foreach($specialistUsers as $item)
                                        <option
                                            value="{{ $item->id }}" {{ request()->specialist_user_id == $item->id ? 'selected' : '' }}>{{ $item->minName }}</option>
                                    @endforeach
                                    <option value="null">Не назначен</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        @if(count(request()->all()) > 0)
                            <a href="{{ route('lid.index') }}" class="btn btn-sm btn-danger me-3">Сбросить все</a>
                        @endif
                        <button class="btn btn-sm btn-success">Искать</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-2">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                            <div class="px-3 py-2 shadow border bg-white rounded">
                                <div class="text-24">
                                    @foreach($analytics as $key => $item)
                                        {{ $key == 0 ? '' : '|' }} <strong>{{ $item['advertising_company'] }}
                                            :</strong> {{ $item['count'] }}
                                    @endforeach
                                </div>
                                <div class="text-12 nowrap-dot">За все время:</div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                            <div class="px-3 py-2 shadow border bg-white rounded">
                                <div class="text-24">
                                    @foreach($analyticsCurrentMonth as $key => $item)
                                        {{ $key == 0 ? '' : '|' }} <strong>{{ $item['advertising_company'] }}
                                            :</strong> {{ $item['count'] }}
                                    @endforeach
                                </div>
                                <div class="text-12 nowrap-dot">Текущий месяц:</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100">
            <div class="card shadow border bg-white rounded mb-0">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Лиды</h4>
                        <div class="d-flex align-items-center">
                            <div class="me-3">Всего записей: <strong>{{ $lids->total() }}</strong></div>
                            <div class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#create_lid">
                                Создать заявку
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="w-100 d-flex justify-content-center mb-3">
                        {{ $lids->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                    <div class="table-responsive" style="height: calc(100vh - 297px);">
                        <table id="basic-datatables"
                               {{--                               class="fixtable display table table-cut table-head-bg-info table-center">--}}
                               class="display table table-head-bg-info table-center table-cut">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center" style="width: 60px;"><i class="fas fa-pen"></i></th>
                                <th style="width: 50px;">Рек. комп.</th>
                                <th>Ресурс</th>
                                <th class="table-fixed">Имя/Ссылка</th>
                                <th style="min-width: 80px;">Место вед. диалога</th>
                                <th style="min-width: 100px;">Ссылка на лида</th>
                                <th style="min-width: 130px;">Услуга</th>
                                <th style="min-width: 40px;"><i class="fas fa-user-edit"></i></th>
                                <th style="min-width: 250px;">Статус / Состояние</th>
                                <th style="min-width: 40px;"><i class="fas fa-star"></i></th>
                                <th>Прописать лиду</th>
                                <th style="min-width: 120px;">Аудит</th>
                                <th style="min-width: 200px;">Статус спец. / Состояние спец.</th>
                                <th style="min-width: 120px;">Задача спец.</th>
                                <th style="min-width: 125px;">Созвон</th>
                                <th style="min-width: 100px;">Дата и время созвона</th>
                                <th style="min-width: 130px;">Ссылка на сайт</th>
                                <th style="min-width: 130px;">Регион</th>
                                <th style="min-width: 130px;">Цена</th>
                                <th style="min-width: 130px;">Сфера бизнеса</th>
                                <th style="min-width: 80px;">Дата[<br>передан<br>принят<br>сделан]</th>
                                <th style="min-width: 140px;">Специалист</th>
                                <th style="min-width: 130px;">Итоги созвона</th>
                                <th style="min-width: 130px;">Дата поступления</th>
                                <th style="min-width: 100px;">Создал</th>
                                @if(auth()->user()->hasRole('Администратор'))
                                    <th class="text-center" style="width: 60px;"><i class="fas fa-trash"></i></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lids as $key => $lid)

                                @if($key == 0 || $lid['date_receipt'] != $lids[$key - 1]['date_receipt'])
                                    <tr>
                                        <td class="text-center font-weight-bold"
                                            style="font-size: 16px!important; background-color: #48abf750!important; color: #000000;"
                                            colspan="8">
                                            {{ date('d.m.Y', strtotime($lid->date_receipt)) }}
                                        </td>
                                        <td class="text-center font-weight-bold"
                                            style="font-size: 16px!important; background-color: #48abf750!important; color: #000000;"
                                            colspan="8">
                                            {{ date('d.m.Y', strtotime($lid->date_receipt)) }}
                                        </td>
                                        <td class="text-center font-weight-bold"
                                            style="font-size: 16px!important; background-color: #48abf750!important; color: #000000;"
                                            colspan="15">
                                            {{ date('d.m.Y', strtotime($lid->date_receipt)) }}
                                        </td>
                                    </tr>
                                @endif

                                <tr data-id="{{ $lid->id }}" @if($lid['interesting']) class="interesting" @endif
                                @if($key != 0 && $lids[$key - 1]['advertising_company'] != $lid['advertising_company'] && $lids[$key - 1]['date_receipt'] == $lid['date_receipt']) style="border-top: 5px solid #48abf750;"
                                    @endif
                                    data-url="{{ route('lid.partial_update', ['id' => $lid->id]) }}"
                                >
                                    <td class="text-center">{{ $lid->id }}</td>

                                    <td class="text-center">
                                        <div class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                             data-bs-target="#edit_lid" data-id="{{ $lid->id }}"><i
                                                class="fas fa-pen"></i></div>
                                    </td>

                                    <td class="text-center">{{ $lid->advertising_company }}</td>

                                    <td>{{ $lid->resource->name ?? '' }}</td>

                                    <td class="table-fixed">{{ $lid->name_link ?? '' }}</td>

                                    <td>
                                        <select class="form-select form-select-sm" name="location_dialogue_id" id="">
                                            <option value="">-</option>
                                            @foreach($locationDialogues as $item)
                                                <option
                                                    value="{{ $item->id }}" {{ $lid->location_dialogue_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <textarea class="textarea-table" name="link_lid" id="" cols="15"
                                                  rows="2">{{ $lid->link_lid ?? '' }}</textarea>
                                    </td>

                                    <td>
                                        <select class="form-select form-select-sm" name="service_id">
                                            <option value="">-</option>
                                            @foreach($services as $item)
                                                <option
                                                    value="{{ $item->id }}" {{ $lid->service_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="write_lid" class="checkbox"
                                               @if($lid->write_lid) checked @endif>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm select2-with-color"
                                                name="lid_status_id" id="">
                                            <option value="">-</option>
                                            @foreach($lidStatuses as $item)
                                                <option
                                                    value="{{ $item->id }}"
                                                    {{ $lid->lid_status_id == $item->id ? 'selected' : '' }}
                                                    data-color="{{ $item->color ?? '' }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <textarea class="textarea-table w-100" name="state"
                                                  rows="2">{{ $lid->state ?? '' }}</textarea>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="interesting" class="checkbox"
                                               @if($lid->interesting) checked @endif>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control form-control-sm" name="date_write_lid"
                                               value="{{ $lid->date_write_lid }}">
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm select2-with-color-t" name="audit_id"
                                                id="">
                                            <option value="">-</option>
                                            @foreach($audits as $item)
                                                <option
                                                    value="{{ $item->id }}"
                                                    {{ $lid->audit_id == $item->id ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm select2-with-color-t"
                                                name="lid_specialist_status_id">
                                            <option value="">-</option>
                                            @foreach($lidSpecialistStatus as $item)
                                                <option
                                                    value="{{ $item->id }}"
                                                    {{ $lid->lid_specialist_status_id == $item->id ? 'selected' : '' }}
                                                    data-color="{{ $item->color ?? '' }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <textarea class="textarea-table w-100" name="state_specialist"
                                                  rows="2">{{ $lid->state_specialist ?? '' }}</textarea>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" name="specialist_task_id" id="">
                                            <option value="">-</option>
                                            @foreach($specialistTasks as $item)
                                                <option
                                                    value="{{ $item->id }}" {{ $lid->specialist_task_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm select2-with-color-t"
                                                name="call_up_id">
                                            <option value="">-</option>
                                            @foreach($callUps as $item)
                                                <option
                                                    value="{{ $item->id }}"
                                                    {{ $lid->call_up_id == $item->id ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><textarea class="textarea-table" name="date_time_call_up" cols="15"
                                                  rows="2">{{ $lid->date_time_call_up ?? '' }}</textarea></td>
                                    <td>
                                        <textarea class="textarea-table w-100" name="link_to_site"
                                                  rows="2">{{ $lid->link_to_site ?? '' }}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="textarea-table w-100" name="region"
                                                  rows="2">{{ $lid->region ?? '' }}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="textarea-table w-100" name="price"
                                                  rows="2">{{ $lid->price ?? '' }}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="textarea-table w-100" name="business_are"
                                                  rows="2">{{ $lid->business_are ?? '' }}</textarea>
                                    </td>

                                    <td>
                                        <span
                                            class="nowrap">{{ $lid->transfer_date ? date('d.m.Y', strtotime($lid->transfer_date)) : '---' }}</span><br>
                                        <span
                                            class="nowrap">{{ $lid->date_acceptance ? date('d.m.Y', strtotime($lid->date_acceptance)) : '---' }}</span><br>
                                        <span
                                            class="nowrap">{{ $lid->ready_date ? date('d.m.Y', strtotime($lid->ready_date)) : '---' }}</span>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" name="specialist_user_id" id="">
                                            <option value="">-</option>
                                            @foreach($specialistUsers as $item)
                                                <option
                                                    value="{{ $item->id }}" {{ $lid->specialist_user_id == $item->id ? 'selected' : '' }}>{{ $item->minName }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <textarea class="textarea-table w-100" name="result_call"
                                                  rows="2">{{ $lid->result_call ?? '' }}</textarea>
                                    </td>

                                    <td>{{ $lid['date_receipt'] }}</td>

                                    <td>{{ $lid->createUser->minName ?? '' }}</td>

                                    @if(auth()->user()->hasRole('Администратор'))
                                        <td class="text-center">
                                            <form action="{{ route('lid.destroy', ['lid' => $lid->id ]) }}"
                                                  method="post">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="window.confirmDelete()">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100 d-flex justify-content-center mt-3">
                        {{ $lids->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Window.Lid.create', [
    'advertisingCompany' => $advertisingCompany,
    'resources' => $resources,
    'lidStatuses' => $lidStatuses,
    ])

    @include('Window.Lid.edit')

@endsection
@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/lid.js') }}?v=@version"></script>
    <script>
        const getLitInfoURL = '{{ route('lid.get_by_id_html') }}';

        window.confirmDelete = function () {
            var res = confirm('Вы действительно хотите удалить этого лида?')
            if (!res) {
                event.preventDefault();
            }
        }

        $('input[name="interesting"]').change(function () {
            const element = $(this)
            if (element.is(':checked')) {
                element.parent('td').parent('tr').addClass('interesting')
            } else {
                element.parent('td').parent('tr').removeClass('interesting')
            }
        })

        window.formatState = function (state) {
            if (!state.id) {
                return state.text;
            }
            let color = state.element.dataset.color;
            if (color !== '' && color !== undefined) {
                return $("<span class='nowrap select-2-custom-state-color' style='background-color: " + color + "; '>" + state.text + "</span>");
            } else {
                return state.text
            }
        }

        $('.select2-with-color').select2({
            templateSelection: window.formatState,
            templateResult: window.formatState,
            minimumResultsForSearch: -1
        })

        $('table .select2-with-color-t').select2({
            templateSelection: window.formatState,
            templateResult: window.formatState,
            minimumResultsForSearch: -1
        })


        $('.table-responsive').on('scroll', function() {
            const stickyTD = $('.table-fixed');
            const tableResponsive = $('.table-responsive');

            if(stickyTD.offset().left === tableResponsive.offset().left){
                stickyTD.addClass('sticked')
            }else{
                stickyTD.removeClass('sticked')
            }
        })

    </script>

@endsection

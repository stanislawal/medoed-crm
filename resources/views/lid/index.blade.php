@extends('layout.markup')
@section('title', 'База лидов')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        .textarea-table {
            border: 1px solid #ced4da;
            border-radius: 3px;
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
                <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                        class="fa fa-search search-icon mr-2"></i>Поиск
                </div>

                <form action="" class="check__field">
                    @csrf
                    <div class="row m-0" id="search">
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
                        <div class="form-group col-12 col-md-4 col-lg-3">
                            <label class="form-label">Имя/Ссылка</label>
                            <div class="input-group">
                                <input type="text" name="name_link" class="form-control form-control-sm"
                                       value="{{ request()->name_link ?? '' }}">
                            </div>
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
                        <div class="col-12">
                            <div class="px-3 py-2 shadow border bg-white rounded d-flex justify-content-center">
                                <div style="width: 200px" class="d-flex justify-content-between">
                                    @foreach($analytics as $item)
                                        <div>
                                            <strong>{{ $item['advertising_company'] }}:</strong>
                                            {{ $item['count'] }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100">
            <div class="card shadow border bg-white rounded">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Лиды</h4>
                        <div class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#create_lid">
                            Создать заявку
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="w-100 d-flex justify-content-center mb-3">
                        {{ $lids->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                    <div class="table-responsive">
                        <table id="basic-datatables"
                               class="display table table-cut table-head-bg-info table-center">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center" style="width: 60px;"><i class="fas fa-pen"></i></th>
                                <th style="width: 50px;">Рек. комп.</th>
                                <th>Ресурс</th>
                                <th>Имя/Ссылка</th>
                                <th style="min-width: 125px;">Место вед. диалога</th>
                                <th style="min-width: 100px;">Ссылка на лида</th>
                                <th style="min-width: 130px;">Услуга</th>
                                <th style="min-width: 40px;"></th>
                                <th style="min-width: 200px;">Статус / Состояние</th>
                                <th style="min-width: 160px;">Созвон</th>
                                <th style="min-width: 100px;">Дата и время созвона</th>
                                <th style="min-width: 160px;">Аудит</th>
                                <th style="min-width: 120px;">Задача спец.</th>
                                <th style="min-width: 200px;">Статус спец. / Состояние спец.</th>
                                <th style="min-width: 130px;">Ссылка на сайт</th>
                                <th style="min-width: 130px;">Регион</th>
                                <th style="min-width: 130px;">Цена</th>
                                <th style="min-width: 130px;">Сфера бизнеса</th>
                                <th style="min-width: 80px;">Дата[<br>передан<br>принят<br>сделан]</th>
                                <th style="min-width: 140px;">Специалист</th>
                                <th style="min-width: 130px;">Итоги созвона</th>
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
                                            style="font-size: 16px!important; background-color: #48abf750; color: #000000;"
                                            colspan="8">
                                            {{ date('d.m.Y', strtotime($lid->date_receipt)) }}
                                        </td>
                                        <td class="text-center font-weight-bold"
                                            style="font-size: 16px!important; background-color: #48abf750; color: #000000;"
                                            colspan="8">
                                            {{ date('d.m.Y', strtotime($lid->date_receipt)) }}
                                        </td>
                                        <td class="text-center font-weight-bold"
                                            style="font-size: 16px!important; background-color: #48abf750; color: #000000;"
                                            colspan="8">
                                            {{ date('d.m.Y', strtotime($lid->date_receipt)) }}
                                        </td>
                                    </tr>
                                @endif

                                <tr data-id="{{ $lid->id }}"
                                    @if($key != 0 && $lids[$key - 1]['advertising_company'] != $lid['advertising_company']) style="border-top: 2px solid #a9a8a8;"
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

                                    <td>{{ $lid->name_link ?? '' }}</td>

                                    <td>
                                        <select class="form-select form-select-sm" name="location_dialogue_id" id="">
                                            <option value="">Не выбрано</option>
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
                                            <option value="">Не выбрано</option>
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
                                            <option value="">Не выбрано</option>
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
                                        <select class="form-select form-select-sm select2-with-color-t"
                                                name="call_up_id">
                                            <option value="">Не выбрано</option>
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
                                        <select class="form-select form-select-sm select2-with-color-t" name="audit_id"
                                                id="">
                                            <option value="">Не выбрано</option>
                                            @foreach($audits as $item)
                                                <option
                                                    value="{{ $item->id }}"
                                                    {{ $lid->audit_id == $item->id ? 'selected' : '' }} data-color="{{ $item->color }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" name="specialist_task_id" id="">
                                            <option value="">Не выбрано</option>
                                            @foreach($specialistTasks as $item)
                                                <option
                                                    value="{{ $item->id }}" {{ $lid->specialist_task_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm select2-with-color-t"
                                                name="lid_specialist_status_id">
                                            <option value="">Не выбрано</option>
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
                                            <option value="">Не выбрано</option>
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
                    <div class="w-100 d-flex justify-content-center">
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
    </script>

@endsection

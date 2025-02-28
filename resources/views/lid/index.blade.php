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
                                       value="{{ request()->month ?? now()->format('n')}}">
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
                               class="display table table-cut table-hover table-head-bg-info table-center">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center" style="width: 60px;"><i class="fas fa-pen"></i></th>
                                <th style="width: 50px;">Рек. комп.</th>
                                <th style="width: 90px;">Дата поступления</th>
                                <th>Ресурс</th>
                                <th>Имя/Ссылка</th>
                                <th>Место вед. диалога</th>
                                <th>Ссылка на лида</th>
                                <th>Услуга</th>
                                <th>Созвон</th>
                                <th>Дата и время созвона</th>
                                <th>Аудит</th>
                                <th>Задача специалиста</th>
                                <th>Дата[<br>передан<br>принят<br>сделан]</th>
                                <th>Специалист</th>
                                <th>Прописка</th>
                                <th>Статус / Состояние</th>
                                <th>Ссылка на сайт</th>
                                <th>Регион</th>
                                <th>Цена</th>
                                <th>Сфера бизнеса</th>
                                <th>Создал</th>
                                @if(auth()->user()->hasRole('Администратор'))
                                    <th class="text-center" style="width: 60px;"><i class="fas fa-trash"></i></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lids as $key => $lid)

                                {{--                                @if()--}}

                                <tr data-id="{{ $lid->id }}">
                                    <td class="text-center">{{ $lid->id }}</td>

                                    <td class="text-center">
                                        <div class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                             data-bs-target="#edit_lid" data-id="{{ $lid->id }}"><i
                                                    class="fas fa-pen"></i></div>
                                    </td>

                                    <td class="text-center">{{ $lid->advertising_company }}</td>

                                    <td class="text-center">{{ date('d.m.Y', strtotime($lid->date_receipt)) }}</td>

                                    <td>{{ $lid->resource->name ?? '' }}</td>

                                    <td>{{ $lid->name_link ?? '' }}</td>

                                    <td>{{ $lid->locationDialogue->name ?? '' }}</td>

                                    <td>{{ $lid->link_lid ?? '' }}</td>

                                    <td>{{ $lid->service->name ?? '' }}</td>

                                    <td>
                                        @if($lid->callUp?->color)
                                            <span class="select-2-custom-state-color nowrap px-2 me-1"
                                                  style="background-color: {{ $lid->callUp->color ?? '' }};">{{ $lid->callUp->name ?? '' }}</span>
                                        @else
                                            {{ $lid->callUp->name ?? '' }}
                                        @endif
                                    </td>
                                    <td>{{ $lid->date_time_call_up ?? '' }}</td>
                                    <td>
                                        @if($lid->audit?->color)
                                            <span class="select-2-custom-state-color nowrap px-2 me-1"
                                                  style="background-color: {{ $lid->audit->color ?? '' }};">{{ $lid->audit->name ?? '' }}</span>
                                        @else
                                            {{ $lid->audit->name ?? '' }}
                                        @endif
                                    </td>
                                    <td>{{ $lid->specialistTask->name ?? '' }}</td>
                                    <td>
                                        <span
                                                class="nowrap">{{ $lid->transfer_date ? date('d.m.Y', strtotime($lid->transfer_date)) : '---' }}</span><br>
                                        <span
                                                class="nowrap">{{ $lid->date_acceptance ? date('d.m.Y', strtotime($lid->date_acceptance)) : '---' }}</span><br>
                                        <span
                                                class="nowrap">{{ $lid->ready_date ? date('d.m.Y', strtotime($lid->ready_date)) : '---' }}</span>
                                    </td>
                                    <td>{{ $lid->specialistUser->minName ?? '' }}</td>
                                    <td class="text-center" style="color:#606060;">
                                        @if($lid->write_lid)
                                            <i class="fas fa-check-square text-22"></i>
                                        @else
                                            <i class="far fa-square text-22"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="select-2-custom-state-color nowrap px-2 me-1"
                                              style="background-color: {{ $lid->lidStatus->color ?? '' }};">{{ $lid->lidStatus->name ?? 'Не указан' }}</span>
                                        {{ $lid->state }}
                                    </td>
                                    <td>{{ $lid->link_to_site ?? '' }}</td>
                                    <td>{{ $lid->region ?? '' }}</td>
                                    <td>{{ $lid->price ?? '' }}</td>
                                    <td>{{ $lid->business_are ?? '' }}</td>

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
                return $("<span class='select-2-custom-state-color' style='background-color: " + color + "; '>" + state.text + "</span>");
            } else {
                return state.text
            }
        }

        $('.select2-with-color').select2({
            templateSelection: window.formatState,
            templateResult: window.formatState
        })
    </script>

@endsection

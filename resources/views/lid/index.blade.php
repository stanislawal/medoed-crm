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
                                <th style="width: 90px;">Рекламмная компания</th>
                                <th style="width: 90px;">Дата поступления</th>
                                <th>Ресурс</th>
                                <th>Имя/Ссылка</th>
                                <th class="text-center">Статус</th>
                                <th>Состояние</th>
                                <th>Создал</th>
                                <th class="text-center" style="width: 60px;"><i class="fas fa-trash"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lids as $lid)
                                <tr data-id="{{ $lid->id }}">
                                    <td class="text-center">{{ $lid->id }}</td>
                                    <td class="text-center">
                                        <div class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                             data-bs-target="#edit_lid" data-id="{{ $lid->id }}"><i
                                                class="fas fa-pen"></i></div>
                                    </td>
                                    <td class="text-center">{{ $lid->advertising_company }}</td>
                                    <td>{{ $lid->date_receipt }}</td>
                                    <td>{{ $lid->resource->name ?? '' }}</td>
                                    <td>{{ $lid->name_link }}</td>
                                    <td class="text-center"><span class="select-2-custom-state-color" style="background-color: {{ $lid->lidStatus->color ?? '' }};">{{ $lid->lidStatus->name ?? 'Не указан' }}</span></td>
                                    <td>{{ $lid->state }}</td>
                                    <td>{{ $lid->createUser->minName }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('lid.destroy', ['lid' => $lid->id ]) }}" method="post">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="window.confirmDelete()"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
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

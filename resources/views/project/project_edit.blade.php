@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')

    <div class="container">

        <div class="col-12 mb-3">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        <h1 class="mb-3 text-center">Форма редактирования проекта</h1>

        <form action="{{route('project.update', ['project' => $projectInfo['id']])}}" method="POST"
              class="mb-5" data-form-name="edit__project">
            @csrf
            @method('PUT')

            <dic class="d-flex justify-content-end mb-3">
                <div class="btn btn-danger btn-sm mr-3 w-auto" style="display: none;" data-role="cancel"
                     onclick="onEdit('edit__project', true)">Отмена
                </div>
                <button class="btn btn-success btn-sm mr-3 w-auto" style="display: none;">Обновить</button>
                <div class="btn btn-primary btn-sm w-auto" data-role="edit"
                     onclick="onEdit('edit__project', false)">Редактировать
                </div>
            </dic>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Менеджер</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="manager_id" disabled>
                        <option value="">Не выбрано</option>
                        @foreach ($managers as $manager)
                            <option @if($manager['id'] == $projectInfo['manager_id']) selected
                                    @endif value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Тема</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="theme_id" disabled
                            value="{{ $projectInfo['theme_id'] ?? '' }}">
                        @foreach ($themes as $theme)
                            <option value="{{$theme['id']}}"
                                    @if($theme['id'] == $projectInfo['theme_id'])
                                        selected
                                @endif
                            >{{$theme['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Название проекта</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="project_name" disabled
                           value="{{ $projectInfo['project_name'] ?? '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Тип текста</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" title="Пожалуйста, выберите"
                            name="style_id"
                            disabled>
                        @foreach ($style as $item)
                            <option value="{{$item['id']}}"
                                    @if($item['id'] == $projectInfo['style_id']) selected @endif
                            >{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Начальный объём проекта</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="total_symbols"
                           disabled value="{{ $projectInfo['total_symbols'] ?? '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Тип задачи</label>
                <div class="col-sm-9">
                    <input type="text" disabled value="{{ $projectInfo['type_task'] ?? '' }}" class="form-control form-control-sm" name="type_task">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дополнительная информация</label>
                <div class="col-sm-9">
                    <textarea type="text" disabled rows="6" style="resize: both;" class="form-control form-control-sm"  name="dop_info">{{ $projectInfo['dop_info'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дата поступления тз</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control form-control-sm" name="start_date_project"
                           disabled value="{{ $projectInfo['start_date_project'] ?? '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Комментарий</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="4" class="form-control form-control-sm" name="comment"
                              placeholder="Укажите комментарий к проекту"
                              disabled>{{ $projectInfo['comment'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Сфера бизнеса</label>
                <div class="col-sm-9">
                    <textarea type="text" disabled
                              class="form-control form-control-sm" name="business_area">{{ $projectInfo['business_area'] }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ссылка на сайт</label>
                <div class="col-sm-9">
                    <input disabled type="text" value="{{ $projectInfo['link_site'] }}"
                           class="form-control form-control-sm" name="link_site">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Перспектива проекта</label>
                <div class="col-sm-9">
                    <textarea disabled type="text"
                              class="form-control form-control-sm" name="project_perspective">{{ $projectInfo['project_perspective'] }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Назначить авторов</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm select-2" multiple name="author_id[]"
                            disabled>
                        @foreach ($authors as $author)
                            <option
                                @if(in_array($author['id'], collect($projectInfo['project_author'])->pluck('id')->toArray()))
                                    selected
                                @endif value="{{$author['id']}}">{{$author['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Цена автора</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm" type="text"
                               name="price_author"
                               disabled value="{{ $projectInfo['price_author'] ?? '' }}">
                        <div class="input-group-append input-group-sm">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Цена заказчика</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm" disabled value="{{ $projectInfo['price_client'] ?? '' }}" type="text"
                               name="price_client">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Состояние проекта</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" name="status_id" disabled>
                        @foreach ($statuses as $status)
                            <option value="{{$status['id']}}"
                                    @if($status['id'] == $projectInfo['status_id'])
                                        selected
                                @endif
                            >{{$status['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="bg-primary">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Цена за 1000 символов</label>
                <div class="col-sm-9">
                    <input type="number" disabled value="{{$projectInfo['price_per']}}"
                           class="form-control form-control-sm" name="price_per">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Как платит</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="pay_info"
                           disabled value="{{ $projectInfo['pay_info'] ?? '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Сроки оплаты</label>
                <div class="col-sm-9">
                    <input disabled type="text" value="{{$projectInfo['payment_terms']}}"
                           class="form-control form-control-sm" name="payment_terms">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Счёт для оплаты</label>
                <div class="col-sm-9">
                    <input disabled type="text" value="{{$projectInfo['invoice_for_payment']}}"
                           class="form-control form-control-sm" name="invoice_for_payment">
                </div>
            </div>

            <hr class="bg-primary">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Заказчики</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm select-2" multiple
                            title="Пожалуйста, выберите"
                            name="client_id[]" disabled>
                        <option value="">Не выбрано</option>
                        @foreach ($clients as $client)
                            <option value="{{$client['id']}}"
                                    @if(in_array($client['id'], collect($projectInfo['project_clients'])->pluck('id')->toArray()))
                                        selected
                                @endif
                            >{{$client['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{--            <div class="row mb-3">--}}
            {{--                <label class="col-sm-3 col-form-label">Портрет заказчика</label>--}}
            {{--                <div class="col-sm-9">--}}
            {{--                    <input type="text" class="form-control form-control-sm" required value="{{ $projectInfo['characteristic'] }}" name="characteristic">--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Договор</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-contract" name="contract"
                            disabled>
                        <option value="1" @if($projectInfo['contract'] == true) selected @endif>Да
                        </option>
                        <option value="0" @if($projectInfo['contract'] == false) selected @endif>Нет
                        </option>
                    </select>
                    <input type="text"
                           @if(!(boolean)$projectInfo['contract']) style="display: none;" @endif
                           class="form-control input-contract mt-2 form-control-sm"
                           placeholder="Вставьте ссылку на договор"
                           disabled
                           value="{{$projectInfo['contract_exist']}}" name="contract_exist">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Настроение</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" name="mood_id" disabled
                            value="{{ $projectInfo['mood_id'] ?? '' }}">
                        @foreach ($moods as $mood)
                            <option value="{{$mood['id']}}">{{$mood['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <dic class="d-flex justify-content-end">
                <div class="btn btn-danger btn-sm mr-3 w-auto" style="display: none;" data-role="cancel"
                     onclick="onEdit('edit__project', true)">Отмена
                </div>
                <button class="btn btn-success btn-sm mr-3 w-auto" style="display: none;">Обновить</button>
                <div class="btn btn-primary btn-sm w-auto" data-role="edit"
                     onclick="onEdit('edit__project', false)">Редактировать
                </div>
            </dic>
        </form>
    </div>

@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script>
        $('.select-contract').change(function () {
            if ($(this).val() === '0') {
                $('.input-contract').hide();

            } else {
                $('.input-contract').show();
            }
        });
    </script>
@endsection

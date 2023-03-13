@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <h2>Информация о проекте</h2>

    <div class="row m-0">
        <div class="col-12">
            <div class="col-lg-12 p-0">
                @include('Answer.custom_response')
                @include('Answer.validator_response')
            </div>
            <form action="{{route('project.update', ['project' => $projectInfo['id']])}}" method="POST"
                  data-form-name="edit__project">
                @csrf
                @method('PUT')
                <div class="row m-0">
                    <div class="col-12">

                        <div class="shadow border rounded row mb-3">
                            <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">О
                                проекте
                            </div>
                            <div class="w-100 row m-0 p-2">
                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Менеджер</label>
                                    <select class="form-control form-control-sm" name="manager_id" disabled
                                            value="{{ $projectInfo['manager_id'] ?? '' }}">
                                        <option value="">Не выбрано</option>
                                        @foreach ($managers as $manager)
                                            <option value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Тема</label>
                                    <select class="form-control form-control-sm" name="theme_id" disabled
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

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Название проекта</label>
                                    <input type="text" class="form-control form-control-sm" name="project_name" disabled
                                           value="{{ $projectInfo['project_name'] ?? '' }}">
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Тип текста</label>

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

{{--                                <div class="form-group col-12 col-lg-6">--}}
{{--                                    <label for="" class="form-label">Начальный объём проекта</label>--}}
{{--                                    <input type="text" class="form-control form-control-sm" name="total_symbols"--}}
{{--                                           disabled--}}
{{--                                           value="{{ $projectInfo['total_symbols'] ?? '' }}">--}}
{{--                                </div>--}}

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Дата поступления тз</label>
                                    <input type="date" class="form-control form-control-sm" name="start_date_project"
                                           disabled value="{{ $projectInfo['start_date_project'] ?? '' }}">
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Комментарий</label>
                                    <textarea type="text" rows="4" class="form-control form-control-sm" name="comment"
                                              placeholder="Укажите комментарий к проекту"
                                              disabled>{{ $projectInfo['comment'] ?? '' }}</textarea>
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Сфера бизнесса</label>
                                    <input type="text" disabled value="{{ $projectInfo['business_area'] }}" class="form-control form-control-sm" name="business_area">
                                </div>


                                <div class="form-group col-12 col-lg-6">
                                    <label for=""  class="form-label">Ссылка на сайт</label>
                                    <input disabled type="text" value="{{ $projectInfo['link_site'] }}" class="form-control form-control-sm" name="link_site">
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Перспектива проекта</label>
                                    <input disabled type="text" value="{{ $projectInfo['project_perspective'] }}" class="form-control form-control-sm" name="project_perspective">
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Назначить авторов</label>
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

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Состояние проекта </label>
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
                        </div>

                        <div class="shadow border rounded row mb-3">
                            <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">
                                Условия оплаты
                            </div>
                            <div class="w-100 row m-0 p-2">
                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Цена за 1000 символов</label>
                                    <input type="number" disabled value="{{$projectInfo['price_per']}}"  class="form-control form-control-sm" name="price_per">
                                </div>


                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Цена автора</label>
                                    <div class="input-group mb-3">
                                        <input class="form-control form-control-sm" type="number" step="0.1" min="0.1"
                                               name="price_author"
                                               disabled value="{{ $projectInfo['price_author'] ?? '' }}">
                                        <div class="input-group-append input-group-sm">
                                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Как платит</label>
                                    <input type="text" class="form-control form-control-sm" name="pay_info"
                                           disabled value="{{ $projectInfo['pay_info'] ?? '' }}">
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Сроки оплаты</label>
                                    <input disabled type="text" value="{{$projectInfo['payment_terms']}}"  class="form-control form-control-sm" name="payment_terms">
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Счёт для оплаты</label>
                                    <input disabled type="text" value="{{$projectInfo['invoice_for_payment']}}"  class="form-control form-control-sm" name="invoice_for_payment">
                                </div>

                            </div>
                        </div>
                        <div class="shadow border rounded row mb-3">
                            <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">
                                Заказчик
                            </div>
                            <div class="w-100 row m-0 p-2">


                                <div class="form-group col-12">
                                    <label for="" class="form-label">Заказчики</label>
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

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Договор</label>
                                    <select class="form-select form-select-sm select-contract" name="contract"
                                            disabled>
                                        <option value="1" @if($projectInfo['contract'] == true) selected @endif>Да
                                        </option>
                                        <option value="0" @if($projectInfo['contract'] == false) selected @endif>Нет
                                        </option>
                                    </select>
                                        <input type="text"
                                                @if(!(boolean)$projectInfo['contract']) style="display: none;" @endif
                                               class="form-control input-contract mt-1 form-control-sm"
                                               placeholder="Вставьте ссылку на договор"
                                               disabled
                                               value="{{$projectInfo['contract_exist']}}" name="contract_exist">

                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    <label for="" class="form-label">Настроение</label>
                                    <select class="form-control form-control-sm" name="mood_id" disabled
                                            value="{{ $projectInfo['mood_id'] ?? '' }}">
                                        @foreach ($moods as $mood)
                                            <option value="{{$mood['id']}}">{{$mood['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="shadow border rounded row mb-3">
                            <div class="w-100 row m-0 p-3">
                                <div class="btn btn-primary btn-sm mr-3 w-auto" data-role="edit"
                                     onclick="onEdit('edit__project', false)">
                                    Редактировать
                                </div>
                                <button class="btn btn-success btn-sm mr-3 w-auto" style="display: none;">Обновить
                                </button>
                                <div class="btn btn-danger btn-sm mr-3 w-auto" style="display: none;" data-role="cancel"
                                     onclick="onEdit('edit__project', true)">Отмена
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


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

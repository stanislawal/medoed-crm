@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="container">

        <div class="row mb-1">
            <div class="col-lg-9 p-0">
                @include('Answer.custom_response')
                @include('Answer.validator_response')
            </div>
        </div>

        <h1 class="mb-3 text-center">Форма создания проекта</h1>

        <form action="{{route('project.store')}}" method="POST" class="mb-5 p-3 border shadow bg-white">
            @csrf

            <div class="text-18 font-weight-bold mb-3 text-center" style="background-color: #f1c232">
                Информация о проекте
            </div>

            <hr class="bg-black">

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Название проекта <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" required name="project_name">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Название компании (Бренда)</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="company_name">
                    <label class="form-check-label mt-1 user-select-none" style="padding-left: 20px;">
                        <input class="form-check-input parse_check" type="checkbox" value="" name="company_name_parse">
                        <span class="form-check-sign">Перенести с заказчика</span>
                    </label>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Дата поступления проекта</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control form-control-sm" name="start_date_project"
                           value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Менеджер</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="manager_id">
                        <option value="">Не выбрано</option>
                        @foreach ($managers as $manager)
                            <option value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Автор в проекте</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-2" multiple name="author_id[]">
                        <option value="">Не выбрано</option>
                        @foreach ($authors as $author)
                            <option value="{{$author['id']}}">{{$author['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Команда проекта</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="project_team">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Классификация</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="theme_id">
                        <option value="">Не выбрано</option>
                        @foreach ($themes as $theme)
                            <option value="{{$theme['id']}}">{{$theme['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Приоритетность</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" title="Пожалуйста, выберите" name="style_id">
                        <option value="">Не выбрано</option>
                        @foreach ($style as $item)
                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ссылка на сайт</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="link_site">
                    <label class="form-check-label mt-1 user-select-none" style="padding-left: 20px;">
                        <input class="form-check-input parse_check" type="checkbox" value="" name="link_site_parse">
                        <span class="form-check-sign">Перенести с заказчика</span>
                    </label>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Ссылка на ресурсы компании (соцсети, каналы)</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="link_to_resources">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">СМИ в которых были публикации/ссылки</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm"
                           name="mass_media_with_publications">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Площадка размещения нашего контента</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="content_public_platform">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Сфера бизнеса</label>
                <div class="col-sm-9">
                    <textarea type="text" class="form-control form-control-sm" name="business_area"></textarea>
                    <label class="form-check-label mt-1 user-select-none" style="padding-left: 20px;">
                        <input class="form-check-input parse_check" type="checkbox" value="" name="business_area_parse">
                        <span class="form-check-sign">Перенести с заказчика</span>
                    </label>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Продукт, который продает компания</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="product_company">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Задача заказчика</label>
                <div class="col-sm-9">
                    <textarea class="form-control form-control-sm" style="resize: vertical!important;"
                              name="task_client"></textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Задача проекта</label>
                <div class="col-sm-9">
                    <textarea class="form-control form-control-sm" style="resize: vertical!important;"
                              name="type_task"></textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Перспектива данная клиентом</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="2" style="resize: vertical;" class="form-control form-control-sm"
                              name="project_perspective"> </textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Перспектива проекта (как ее видит аккаунт)</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="2" style="resize: vertical;" class="form-control form-control-sm"
                              name="project_perspective_sees_account"> </textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Доп. информация о проекте</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="2" style="resize: vertical;" class="form-control form-control-sm"
                              name="dop_info"></textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Состояние проекта <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" required name="status_id" id="">
                        @foreach ($statuses as $status)
                            <option value="{{$status['id']}}"
                                    @if($status['id'] == \App\Constants\StatusConstants::DRAFT)
                                        selected
                                @endif
                            >{{$status['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Текущие задачи</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="2" class="form-control form-control-sm" name="comment"
                              placeholder="Комментарий"></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Напоминание</label>
                <div class="col-sm-9">
                    <input type="date"
                           class="form-control form-control-sm" name="date_connect_with_client">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">План на месяц</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="2" class="form-control form-control-sm" name="project_status_text"
                              placeholder="Укажите комментарий к проекту"></textarea>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Созвон</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="call_up">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дата последнего контакта</label>
                <div class="col-sm-9">
                    <input type="date"
                           class="form-control form-control-sm" name="date_last_change">
                </div>
            </div>


            <hr class="bg-black">

            <div class="text-18 font-weight-bold mb-3 text-center" style="background-color: #f1c232">
                Для спецпроектов
            </div>
            <hr class="bg-black">

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Отдел </label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select2-with-color" name="service_type_id">
                        <option value="">Не выбрано</option>
                        @foreach($serviceTypes as $item)
                            <option data-color="{{ $item->color }}" value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Тема проекта </label>
                <div class="col-sm-9">
                    <input class="form-control form-control-sm" type="text" name="project_theme_service" >
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Отчетная дата</label>
                <div class="col-sm-9">
                    <input class="form-control form-control-sm" type="date" name="reporting_data">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Условия оплаты</label>
                <div class="col-sm-9">
                    <input class="form-control form-control-sm" type="text" name="terms_payment">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Регион продвижения</label>
                <div class="col-sm-9">
                    <input class="form-control form-control-sm" type="text" name="region">
                </div>
            </div>


            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Ссылка на план работы</label>
                <div class="col-sm-9">
                    <input class="form-control form-control-sm" type="text" name="passport_to_work_plan">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Часы</label>
                <div class="col-sm-9">
                    <input class="form-control form-control-sm" type="number" name="hours">
                </div>
            </div>

{{--            <div class="row mb-1">--}}
{{--                <label class="col-sm-3 col-form-label">Общая сумма договора</label>--}}
{{--                <div class="col-sm-9">--}}
{{--                    <input class="form-control form-control-sm" type="number" name="total_amount_agreement">--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Ведущий специалист</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select2-with-color" name="leading_specialist_id">
                        <option value="">Не выбрано</option>
                        @foreach($specialists as $item)
                            <option data-color="{{ $item->color }}" value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Продвигаем сайт</label>
                <div class="col-sm-9">
                    <input class="form-control form-control-sm" type="text" name="promoting_website">
                </div>
            </div>

            <hr class="bg-black">

            <div class="text-18 font-weight-bold mb-3 text-center" style="background-color: #f1c232">
                Условия оплаты
            </div>

            <hr class="bg-black">

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Цена заказчика</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm" type="text"
                               name="price_client">
                        <div class="input-group-append input-group-sm">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Цена автора</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm" step="0.01" type="number"
                               name="price_author">
                        <div class="input-group-append input-group-sm">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Условия оплаты</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="pay_info">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Сроки оплаты</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="payment_terms">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дата оплаты</label>
                <div class="col-sm-9">
                    <input type="date"
                           class="form-control form-control-sm" name="date_notification">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дни оплаты</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <div class="w-50">
                            <select class="form-control form-control-sm input-group select-2" multiple name="days[]">
                                <option value="">Не выбрано</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="w-50">
                            <select class="form-control form-control-sm select-2" multiple name="weekday[]">
                                <option value="">Не выбрано</option>
                                @foreach(\App\Helpers\DateHelper::getWeekdayList() as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Счёт для оплаты</label>
                <div class="col-sm-9">
                    <select class="form-select font-select-sm" name="requisite_id">
                        <option value="">Не выбрано</option>
                        @foreach($requisite as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Срок принятия работы (проверки текста)</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="deadline_accepting_work">
                </div>
            </div>


            <hr class="bg-black">

            <div class="text-18 font-weight-bold mb-3 text-center" style="background-color: #f1c232">
                Договор, ЭДО, NDA
            </div>

            <hr class="bg-black">

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Заказчик <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-2" size="5"
                            title="Пожалуйста, выберите" name="client_id[]" required>
                        <option value="">Не выбрано</option>
                        @foreach ($clients as $client)
                            <option value="{{$client['id']}}">{{$client['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Договор</label>
                <div class="col-sm-9">
                    <select class="form-select select-contract form-select-sm" name="contract">
                        <option value="1">Да</option>
                        <option selected value="0">Нет</option>
                    </select>

                    <input type="text"
                           class="form-control input-contract mt-2 form-control-sm d-none"
                           placeholder="Вставьте ссылку на договор"
                           value="" name="contract_exist">

                    <input type="text"
                           class="form-control input-contract mt-2 mb-3 form-control-sm d-none"
                           placeholder="Номер договора"
                           value="" name="contract_number">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Юридическое название компании</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="legal_name_company">
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Подпись NDA</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-contract" name="nds"
                    >
                        <option value="1">
                            Да
                        </option>
                        <option selected value="0">
                            Нет
                        </option>
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Настроение</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" name="mood_id">
                        <option value="">Не выбрано</option>
                        @foreach ($moods as $mood)
                            <option value="{{$mood['id']}}">{{$mood['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">ЭДО</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="edo"
                    >
                        <option value="1">
                            Да
                        </option>
                        <option selected value="0">
                            Нет
                        </option>
                    </select>
                </div>
            </div>

            <div class="row mb-1">
                <label class="col-sm-3 col-form-label">Срок подписания акта выполненных работ</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="period_work_performed">
                </div>
            </div>

            <dic class="d-flex justify-content-end">
                <button type="submit" class="btn btn-sm btn-primary mt-3">Создать</button>
            </dic>
        </form>
    </div>

@endsection

@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{asset('js/select2.js')}}"></script>
    <script>
        $('.select-contract').change(function () {

            console.log();

            if ($(this).val() === '0') {
                $('.input-contract').addClass('d-none');
            } else {
                $('.input-contract').removeClass('d-none');
            }
        });


        $('input.parse_check').click(function () {
            var pattern = '---ПЕРЕНЕСТИ C ЗАКАЗЧИКА---';
            if ($(this).is(':checked')) {
                $(this).closest('.row').find('input, textarea').each(function() {
                    $(this).val(pattern);
                    $(this).attr('readonly', true)
                });
            } else {
                $(this).closest('.row').find('input, textarea').each(function() {
                    if( $(this).val() === pattern){
                        $(this).val('');
                        $(this).removeAttr('readonly')
                    }
                });
            }
        })

    </script>

@endsection

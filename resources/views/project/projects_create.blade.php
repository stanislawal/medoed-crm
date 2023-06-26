@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="container">

        <div class="row mb-3">
            <div class="col-lg-9 p-0">
                @include('Answer.custom_response')
                @include('Answer.validator_response')
            </div>
        </div>

        <h1 class="mb-3 text-center">Форма создания проекта</h1>

        <form action="{{route('project.store')}}" method="POST" class="mb-5">
            @csrf

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Менеджер</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm"  name="manager_id">
                        <option value="">Не выбрано</option>
                        @foreach ($managers as $manager)
                            <option value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Тема</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm"  name="theme_id">
                        <option value="">Не выбрано</option>
                        @foreach ($themes as $theme)
                            <option value="{{$theme['id']}}">{{$theme['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Название проекта</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" required name="project_name">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Тип текста</label>
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
                <label class="col-sm-3 col-form-label">Начальный объём проекта</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="total_symbols">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Тип задачи</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="type_task">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дополнительная информация</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="dop_info">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Перспектива проекта</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="6" style="resize: both;" class="form-control form-control-sm" name="project_perspective"> </textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дата поступления тз</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control form-control-sm" name="start_date_project"
                           value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Комментарий</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="4" class="form-control form-control-sm" name="comment"
                        placeholder="Укажите комментарий к проекту"></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Сфера бизнеса</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="business_area">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ссылка на сайт</label>
                <div class="col-sm-9">
                    <input type="text"  class="form-control form-control-sm" name="link_site">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Назначить авторов</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-2"  multiple name="author_id[]">
                        <option value="">Не выбрано</option>
                        @foreach ($authors as $author)
                            <option value="{{$author['id']}}">{{$author['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Цена автора</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm"  type="number" step="0.1" min="0.1"
                               name="price_author">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Состояние проекта</label>
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

            <hr class="bg-primary">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Цена за 1000 символов</label>
                <div class="col-sm-9">
                    <input type="number"  class="form-control form-control-sm" name="price_per">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Как платит</label>
                <div class="col-sm-9">
                    <input type="text"  class="form-control form-control-sm" name="pay_info">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Сроки оплаты</label>
                <div class="col-sm-9">
                    <input type="text"  class="form-control form-control-sm" name="payment_terms">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Счёт для оплаты</label>
                <div class="col-sm-9">
                    <input type="text"  class="form-control form-control-sm" name="invoice_for_payment">
                </div>
            </div>

            <hr class="bg-primary">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Заказчики</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-2"  multiple size="5"
                            title="Пожалуйста, выберите" name="client_id[]">
                        <option value="">Не выбрано</option>
                        @foreach ($clients as $client)
                            <option value="{{$client['id']}}">{{$client['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Портрет заказчика</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" required name="characteristic">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Договор</label>
                <div class="col-sm-9">
                    <select class="form-select select-contract form-select-sm"  name="contract">
                        <option value="1">Да</option>
                        <option selected value="0">Нет</option>
                    </select>

                    <input type="text"
                           class="form-control input-contract mt-2 form-control-sm d-none"
                           placeholder="Вставьте ссылку на договор"
                           value="" name="contract_exist">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Настроение</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" required name="mood_id">
                        <option value="">Не выбрано</option>
                        @foreach ($moods as $mood)
                            <option value="{{$mood['id']}}">{{$mood['name']}}</option>
                        @endforeach
                    </select>
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
    </script>

@endsection

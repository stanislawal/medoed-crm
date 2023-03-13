@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')

    <h2 class="mb-3">Добавить новый проект</h2>


    <div class="row m-0">
        <div class="col-lg-9 p-0">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>
    </div>

    <form action="{{route('project.store')}}" method="POST">
        @csrf
        <div class="row m-0">
            <div class="col-12">
                <div class="shadow border rounded row mb-3">
                    <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">О проекте
                    </div>

                    <div class="w-100 row m-0 p-2">
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Менеджер</label>
                            <select class="form-select form-select-sm"  name="manager_id">
                                <option value="">Не выбрано</option>
                                @foreach ($managers as $manager)
                                    <option value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Тема</label>
                            <select class="form-control"  name="theme_id">
                                @foreach ($themes as $theme)
                                    <option value="{{$theme['id']}}">{{$theme['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Название проекта</label>
                            <input type="text" class="form-control" required name="project_name">
                        </div>


                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Тип текста</label>

                            <select class="form-control" title="Пожалуйста, выберите" name="style_id">
                                @foreach ($style as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </div>

{{--                        <div class="form-group col-12 col-lg-6">--}}
{{--                            <label for="" class="form-label">Начальный объём проекта</label>--}}
{{--                            <input type="text" class="form-control" name="total_symbols">--}}
{{--                        </div>--}}

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Перспектива проекта</label>
                            <input type="text" class="form-control" name="project_perspective">
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Дата поступления тз</label>
                            <input type="date" class="form-control" name="start_date_project"
                                   value="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Комментарий</label>
                            <textarea type="text" rows="4" class="form-control" name="comment"
                                      placeholder="Укажите комментарий к проекту"></textarea>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Сфера бизнесса</label>
                            <input type="text" class="form-control" name="business_area">
                        </div>


                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Ссылка на сайт</label>
                            <input type="text"  class="form-control" name="link_site">
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Назначить авторов</label>
                            <select class="form-control select-2"  multiple="multiple" name="author_id[]">
                                <option value="">Не выбрано</option>
                                @foreach ($authors as $author)
                                    <option value="{{$author['id']}}">{{$author['full_name']}}</option>
                                @endforeach
                            </select>


                            <label for="" class="form-label">Цена автора</label>
                            <div class="input-group mb-3">
                                <input class="form-control"  type="number" step="0.1" min="0.1"
                                       name="price_author">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">РУБ</span>
                                </div>
                            </div>


                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Состояние проекта </label>
                            <select class="form-control" required name="status_id" id="">
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

                </div>

                <div class="shadow border rounded row mb-3">
                    <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">Условия
                        оплаты
                    </div>
                    <div class="w-100 row m-0 p-2">
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Цена за 1000 символов</label>
                            <input type="number"  class="form-control" name="price_per">
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Как платит</label>
                            <input type="text"  class="form-control" name="pay_info">
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Сроки оплаты</label>
                            <input type="text"  class="form-control" name="payment_terms">
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Счёт для оплаты</label>
                            <input type="text"  class="form-control" name="invoice_for_payment">
                        </div>

                    </div>
                </div>
                <div class="shadow border rounded row mb-3">
                    <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">Заказчик
                    </div>
                    <div class="w-100 row m-0 p-2">

                        <div class="form-group col-12">
                            <label for="" class="form-label">Заказчики</label>
                            <select class="form-control select-2"  multiple size="5"
                                    title="Пожалуйста, выберите"
                                    name="client_id[]">
                                <option value="">Не выбрано</option>
                                @foreach ($clients as $client)
                                    <option value="{{$client['id']}}">{{$client['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{--                        <div class="form-group col-12 col-lg-6">--}}
                        {{--                            <label for="" class="form-label">Портрет заказчика</label>--}}
                        {{--                            <input type="text" class="form-control" required name="characteristic">--}}
                        {{--                        </div>--}}

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Договор</label>
                            <select class="form-select form-select-sm select-contract"  name="contract">

                                <option disabled>Выбрать</option>
                                <option value="1">Да</option>
                                <option selected value="0">Нет</option>
                            </select>
                        </div>

                        <div class="form-group col-12 col-lg-6 d-none input-contract">
                            <label for="" class="form-label">Ссылка на договор</label>
                            <input type="text" class="form-control" name="contract_exist">
                        </div>

                        <div class="form-group col-12  col-lg-6">
                            <label for="" class="form-label">Настроение</label>
                            <select class="form-control" required name="mood_id">
                                @foreach ($moods as $mood)
                                    <option value="{{$mood['id']}}">{{$mood['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <button class="btn btn-success btn-sm mr-3 w-auto">Создать</button>
                        </div>
                    </div>

                </div>


            </div>

        </div>
    </form>

@endsection

@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{asset('js/select2.js')}}"></script>
    <script>
        $('.select-contract').change(function () {
            if ($(this).val() === '0') {
                $('.input-contract').addClass('d-none');
            } else {
                $('.input-contract').removeClass('d-none');
            }
        });
    </script>

@endsection

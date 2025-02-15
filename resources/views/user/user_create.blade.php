@extends('layout.markup')

@section('title')
    Пользователь | {{ config('app.name') }}
@endsection

@section('content')
    <form action="{{route('user.store')}}" method="POST">
        @csrf
        <div class="shadow border rounded row mb-3 bg-white">
            <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">Добавить
                пользователя
            </div>
            <div class="w-100 row m-0 p-2">
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Ф.И.О</label>
                    <input type="text" required class="form-control form-control-sm" name="full_name">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Логин</label>
                    <input type="text" required class="form-control form-control-sm" name="login">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Пароль</label>
                    <input type="password" required class="form-control form-control-sm" name="password">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Контактная информация</label>
                    <input type="text" class="form-control form-control-sm" name="contact_info">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Реквизиты оплаты </label>
                    <div class="input-group">
                        <div class="w-25">
                            <select name="bank_id" class="form-select form-select-sm">
                                <option value="">Выберите банк</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank['id'] }}">{{ $bank['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" class="form-control form-control-sm" name="payment">
                    </div>
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Дата рождения</label>
                    <input type="date" class="form-control form-control-sm" name="birthday">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Роль</label>

                    <select class="form-select form-select-sm select-manager" required name="role" id="">
                        <option value="">Выберите роль</option>
                        @foreach($roles as $role)
                            <option value="{{ $role['name'] }}">{{ $role['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Статус работы</label>
                    <select name="is_work" class="form-select form-select-sm">
                        <option value="1">Работает</option>
                        <option value="0">Не работает</option>
                    </select>
                </div>

                <div class="form-group col-12 col-lg-6 d-none input-manager">
                    <label for="" class="form-label">Ставка менеджера</label>
                    <input type="number" class="form-control form-control-sm" name="manager_salary">
                </div>

                <div class="form-group col-12 col-lg-6 d-none input-author">
                    <label for="" class="form-label">Ссылка на анкету</label>
                    <input type="text" class="form-control form-control-sm" name="link_author">
                </div>

                <div class="form-group col-12 col-lg-6 d-none input-author">
                    <label for="" class="form-label">Рабочий день</label>
                    <input type="text" class="form-control form-control-sm" name="working_day">
                </div>


                <div class="form-group col-12 col-lg-6 d-none input-author">
                    <label for="" class="form-label">ФИО автора (обязательная последовательность)</label>
                    <input type="text" class="form-control form-control-sm" name="fio_for_doc">
                </div>

                <div class="form-group col-12 col-lg-6 d-none input-author">
                    <label for="" class="form-label">ИНН автора</label>
                    <input type="number" class="form-control form-control-sm" name="inn_for_doc"
                           placeholder="____________">
                </div>

                <div class="form-group col-12 col-lg-6 d-none input-author">
                    <label for="" class="form-label">Номер договора автора</label>
                    <input type="text" class="form-control form-control-sm" name="contract_number_for_doc">
                </div>

                <div class="form-group col-12 col-lg-6 d-none input-author">
                    <label for="" class="form-label">Дата составление договора</label>
                    <input type="date" class="form-control form-control-sm" name="date_contract_for_doc">
                </div>

                <div class="form-group col-12 col-lg-6 d-none input-author">
                    <label for="" class="form-label">E-mail автора, для отправки документа</label>
                    <input type="email" class="form-control form-control-sm" name="email_for_doc">
                </div>


                <div class="m-0 p-3">
                    <button class="btn btn-sm btn-success" type="submit">Создать</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $('.select-manager').change(function () {
            if ($(this).val() === 'Менеджер') {
                $('.input-manager').removeClass('d-none');

            } else {
                $('.input-manager').addClass('d-none');
            }
        });
        $('.select-manager').change(function () {
            if ($(this).val() === 'Автор') {
                $('.input-author').removeClass('d-none');

            } else {
                $('.input-author').addClass('d-none');
            }
        });

        $('input[name="inn_for_doc"]').mask('000000000000');
    </script>
@endsection

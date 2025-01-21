@extends('layout.markup')

@section('title')
    Пользователи | {{ config('app.name') }}
@endsection

@section('content')

    <div class="mb-2">
        @include('Answer.custom_response')
        @include('Answer.validator_response')
    </div>
    <form action="{{ route('user.update', ['user' => $user['id']]) }}" method="POST">
        @method('PUT')
        {{--            @dd($user) --}}
        @csrf
        <div class="shadow border rounded row mb-3 bg-white">
            <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">Редактирование
                пользователя
            </div>
            <div class="w-100 row m-0 p-2">
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Ф.И.О</label>
                    <input type="text" value="{{ $user['full_name'] ?? '-' }}" class="form-control form-control-sm"
                           name="full_name">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Логин</label>
                    <input type="text" value="{{ $user['login'] ?? '-' }}" class="form-control form-control-sm"
                           name="login">
                </div>

                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Пароль</label>
                    <div class="input-group">
                        <input type="password" @if($user['id'] == 1000) value="" name="password" disable @else name="password" value="{{ $user['visual_password'] }}" @endif  id="password-input"

                               class="form-control form-control-sm" placeholder="Пароль">
                        <div class="input-group-append">
                            <span class="input-group-text password-toggle-icon"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Контактная информация </label>
                    <input type="text" value="{{ $user['contact_info'] }}" class="form-control form-control-sm"
                           name="contact_info">
                </div>

                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Реквизиты оплаты </label>
                    <div class="input-group">
                        <div class="w-25">
                            <select name="bank_id" class="form-select form-select-sm">
                                <option value="">Выберите банк</option>
                                @foreach ($banks as $bank)
                                    <option {{ $bank['id'] == $user['bank_id'] ? 'selected' : '' }}
                                            value="{{ $bank['id'] }}">{{ $bank['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" value="{{ $user['payment'] }}" class="form-control form-control-sm"
                               name="payment">
                    </div>
                </div>

                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Дата рождения</label>
                    <input type="date" class="form-control form-control-sm" name="birthday"
                           value="{{ $user['birthday'] }}">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Роль</label>
                    <select class="form-select form-select-sm select-manager" name="role" id="">
                        @foreach ($roles as $role)
                            <option @if (\App\Helpers\UserHelper::getRoleName($user['id']) == $role['name']) selected
                                    @endif value="{{ $role['name'] }}">
                                {{ $role['name'] }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Статус работы</label>
                    <select name="is_work" class="form-select form-select-sm">
                        <option value="1" @if($user['is_work'] == 1) selected @endif>Работает</option>
                        <option value="0" @if($user['is_work'] == 0) selected @endif>Не работает</option>
                    </select>
                </div>

                <div
                    class="form-group col-12 col-lg-6 input-manager @if (\App\Helpers\UserHelper::getRoleName($user['id']) != 'Менеджер') d-none @endif">
                    <label for="" class="form-label">Ставка менеджера</label>
                    <input type="number" class="form-control form-control-sm" value="{{ $user['manager_salary'] }}"
                           name="manager_salary">
                </div>

                <div
                    class="form-group col-12 col-lg-6 input-author @if (\App\Helpers\UserHelper::getRoleName($user['id']) != 'Автор') d-none @endif">
                    <label for="" class="form-label">Ссылка на анкету</label>
                    <input type="text" class="form-control form-control-sm" value="{{ $user['link_author'] }}"
                           name="link_author">
                </div>

                <div
                    class="form-group col-12 col-lg-6 input-author @if (\App\Helpers\UserHelper::getRoleName($user['id']) != 'Автор') d-none @endif">
                    <label for="" class="form-label">Рабочий день</label>
                    <input type="text" class="form-control form-control-sm" value="{{ $user['working_day'] }}"
                           name="working_day">
                </div>

                <div class=" m-0 p-3">
                    <button class="btn btn-sm btn-success" type="submit">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_js')
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

        let password_input = document.getElementById("password-input");
        let password_toggle_icon = document.querySelector(".password-toggle-icon");

        password_toggle_icon.addEventListener("click", function () {
            if (password_input.type === "password") {
                password_input.type = "text";
                password_toggle_icon.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                password_input.type = "password";
                password_toggle_icon.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });

    </script>
@endsection

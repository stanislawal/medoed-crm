@extends('layout.markup')

@section('title')
    Пользователи | {{ config('app.name') }}
@endsection

@section('content')
    <form action="{{route('user.store')}}" method="POST">
        @csrf
        <div class="shadow border rounded row mb-3">
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
                    <label for="" class="form-label">Контактная информация </label>
                    <input type="text" required class="form-control form-control-sm" name="contact_info">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Дата рождения</label>
                    <input type="date" required class="form-control form-control-sm" name="birthday">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Роль</label>
                    <select class="form-select form-select-sm select-manager" required name="role" id="">
                        <option disabled value="">Роль</option>
                        <option value="Администратор">Администратор</option>
                        <option value="Менеджер">Менеджер</option>
                        <option value="Автор">Автор</option>
                    </select>
                </div>
                <div class="form-group col-12 col-lg-6 d-none input-manager">
                    <label for="" class="form-label">Ставка менеджера</label>
                    <input type="number" class="form-control form-control-sm" name="manager_salary">
                </div>
                <div class="m-0 p-3">
                    <button class="btn btn-sm btn-success" type="submit">Создать</button>
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
    </script>
@endsection

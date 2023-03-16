@extends('layout.markup')

@section('title')
    Пользователи | {{ config('app.name') }}
@endsection

@section('content')

    <form action="{{route('user.update', ['user' => $user['id']])}}" method="POST">
        @method('PUT')
        {{--            @dd($user)--}}
        @csrf
        <div class="shadow border rounded row mb-3">
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
                    <input type="password" class="form-control form-control-sm" name="password">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Контактная информация </label>
                    <input type="text" value="{{$user['contact_info']}}" class="form-control form-control-sm"
                           name="contact_info">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Реквизиты оплаты </label>
                    <input type="text" value="{{$user['payment']}}"  class="form-control form-control-sm" name="payment">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Дата рождения</label>
                    <input type="date" class="form-control form-control-sm" name="birthday">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Роль</label>
                    <select class="form-select form-select-sm select-manager" name="role" id="">
                        <option disabled value="">Роль</option>

                        <option value="Администратор"
                                @if(\App\Helpers\UserHelper::getRoleName($user['id']) == 'Администратор') selected @endif
                        >Администратор
                        </option>
                        <option value="Менеджер"
                                @if(\App\Helpers\UserHelper::getRoleName($user['id']) == 'Менеджер') selected @endif>
                            Менеджер
                        </option>
                        <option value="Автор"
                                @if(\App\Helpers\UserHelper::getRoleName($user['id']) == 'Автор') selected @endif>Автор
                        </option>
                    </select>
                </div>


                <div class="form-group col-12 col-lg-6 input-manager">
                    <label for="" class="form-label">Ставка менеджера</label>
                    <input type="number" value="{{$user['manager_salary']}}" class="form-control form-control-sm"
                           name="manager_salary">
                </div>

                <div class=" m-0 p-3">
                    <button class="btn btn-sm btn-success" type="submit">Редактировать</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_js')
    {{--    <script>--}}
    {{--        // $('.select-manager').change(function () {--}}
    {{--        //     if ($(this).val() === 'Менеджер') {--}}
    {{--        //         $('.input-manager').removeClass('d-none');--}}
    {{--        //--}}
    {{--        //     } else {--}}
    {{--        //         $('.input-manager').addClass('d-none');--}}
    {{--        //     }--}}
    {{--        // });--}}
    {{--    </script>--}}
@endsection



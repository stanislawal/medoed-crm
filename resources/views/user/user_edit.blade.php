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
                    <div class="input-group">
                        <div class="w-25">
                            <select name="bank_id"  class="form-select form-select-sm">
                                <option value="">Выберите банк</option>
                                @foreach($banks as $bank)
                                    <option {{ $bank['id'] == $user['bank_id'] ? 'selected' : '' }}  value="{{ $bank['id'] }}">{{ $bank['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" value="{{$user['payment']}}"  class="form-control form-control-sm" name="payment">
                    </div>
                </div>

                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Дата рождения</label>
                    <input type="date" class="form-control form-control-sm" name="birthday" value="{{ $user['birthday'] }}">
                </div>
                <div class="form-group col-12 col-lg-6">
                    <label for="" class="form-label">Роль</label>
                    <select class="form-select form-select-sm select-manager" name="role" id="">
                        <option disabled value="">Роль</option>

                        <option  value="">Выберите роль</option>
                        @foreach($roles as $role)
                            <option value="{{ $role['name'] }}">{{ $role['name'] }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group col-12 col-lg-6 d-none input-manager">
                    <label for="" class="form-label">Ставка менеджера</label>
                    <input type="number" class="form-control form-control-sm" value="{{$user['manager_salary']}}" name="manager_salary">
                </div>
                <div class="form-group col-12 col-lg-6 d-none input-author">
                    <label for="" class="form-label">Ссылка на анкету</label>
                    <input type="text" class="form-control form-control-sm" value="{{$user['link_author']}}" name="link_author">
                </div>

                <div class=" m-0 p-3">
                    <button class="btn btn-sm btn-success" type="submit">Редактировать</button>
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
    </script>
@endsection



@extends('layout.markup')

@section('content')
    <div class="row p-0s">
        <div class="col-12">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        <div class="row p-0s">
            <div class="col-12">
                <div class="w-100 shadow border rounded p-3 mb-3">
                    <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                            class="fa fa-search search-icon mr-2"></i>Поиск
                    </div>

                    <form action="{{ route('user.index') }}" method="GET" class="check__field">
                        @csrf
                        <div class="row m-0" id="search" @if(empty(request()->all())) style="display: none;" @endif>
                            <div class="w-100 row m-0 py-3">
                                <div class="form-group col-12 col-md-4 col-lg-3">
                                    <label for="" class="form-label">Должность</label>
                                    <select class="form-select form-select-sm" name="role">
                                        <option>Не выбрано</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role['id'] }}" @if(request()->role == $role['id']) selected @endif>{{$role['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 p-0">
                                <div class="form-group col-12">
                                    <div class="w-100 d-flex justify-content-end">
                                        @if(!empty(request()->all() && count(request()->all())) > 0)
                                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-danger mr-3">Сбросить
                                                фильтр</a>
                                        @endif
                                        <button class="btn btn-sm btn-success">Искать</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Администрирование пользователей</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table  table-hover table-head-bg-info">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Ф.И.О</th>
                                    <th>Логин</th>
                                    <th>Должность</th>
                                    <th>Статус работы</th>
                                    <th>Контактная информация</th>
                                    <th>Дата рождения</th>
                                    <th>Создан</th>
                                    <th>Удалить</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td><a href="{{route('user.edit',['user'=> $user['id']])}}">Открыть</a>
                                        </td>
                                        <td>{{$user['id']}}</td>
                                        <td>{{$user['full_name'] ?? '-'}}</td>
                                        <td>{{$user['login'] ?? '-'}}</td>
                                        <td>{{$user['roles'][0]['name'] ?? '-'}}</td>
                                        <td>
                                            @if((bool)$user['is_work'])
                                                Работает
                                            @else
                                                Не работает
                                            @endif
                                        </td>
                                        <td>{{$user['contact_info'] ?? '-'}}</td>
                                        <td>{{$user['birthday'] ?? '-'}}</td>
                                        <td>{{\Illuminate\Support\Carbon::parse($user['created_at'])->format('d.m.Y H:i') ?? '-'}}</td>
                                        <td>
                                            <div class="form-group col-12 d-flex justify-content-between destroy">
                                                <a href="{{route('user.destroy',['user' => $user['id']])}}"
                                                   class="btn btn-sm btn-outline-danger" onclick="confirmDelete()"><i
                                                        class="fas fa-minus"></i></a>
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @section('custom_js')
            <script>
                window.confirmDelete = function () {
                    var res = confirm('Вы действительно хотите удалить пользователя?')
                    if (!res) {
                        event.preventDefault();
                    }
                }
            </script>
@endsection

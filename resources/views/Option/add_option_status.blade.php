@extends('layout.markup')

@section('content')
<h2>Добавление состояния проекта</h2>
    <div>
        <div class="shadow border p-4 my-3">
                <form action="{{route('add_option_status.store')}}" method="POST">
                    @csrf
                    <div class="mb-3 col-6 col-md-4">
                        <label for="" class="form-label mb-3">Добавить новое состояние проекта</label>
                        <input type="text" class="form-control form-control " name="add_new_status">
                        <button type="success" class="btn btn-sm btn-success mt-3">Добавить</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#id</th>
                            <th>Название состояния</th>
                            <th>Сохранить</th>
                            <th>Удалить</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($statuses as $item)
                        <tr>
                            <form action="{{route('add_option_status.update', ['id' => $item['id']])}}" method="post">
                                @csrf
                                @method('PUT')
                                <td>{{$item['id']}}</td>
                                <td><div><input class="form-control form-control-sm" name="name" value="{{$item['name']}}"></div></td>
{{--                                <td><button class="btn btn-sm btn-success">Сохранить</button></td>--}}
                            </form>

                               <td> <div class="form-group col-12 d-flex justify-content-between destroy">

                                    <a href="{{route('add_option_status.destroy',['status' => $item['id']])}}"
                                       class="btn btn-sm btn-outline-danger"><i
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
@endsection

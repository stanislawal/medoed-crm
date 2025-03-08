@extends('layout.markup')

@section('content')
    <h2>Список ресурсов</h2>
    <div>
        <div class="shadow border p-4 my-3 bg-white">
            <form action="{{route('resource.store')}}" method="POST">
                @csrf
                <div class="mb-3 col-6 col-md-4">
                    <label for="" class="form-label mb-3">Добавить</label>
                    <input type="text" class="form-control form-control" name="name">
                    <button class="btn btn-sm btn-success mt-3">Добавить</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body bg-white">
        <div class="table-responsive">
            <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                <tr>
                    <th>#id</th>
                    <th>Название</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list as $item)
                    <tr>
                        <td>{{$item['id']}}</td>
                        <td>{{$item['name']}}</td>
                        <td>
                            <form action="{{route('resource.destroy', ['resource' => $item['id']])}}" method="post">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-minus"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

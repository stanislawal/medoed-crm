@extends('layout.markup')

@section('content')
<h2>Добавление приоритетности</h2>
    <div>
        <div class="shadow border p-4 my-3 bg-white">
                <form action="{{route('add_option_style.store')}}" method="POST">
                    @csrf
                    <div class="mb-3 col-6 col-md-4">
                        <label for="" class="form-label mb-3">Добавить приоритетность</label>
                        <input type="text" class="form-control form-control " name="add_new_style">
                        <button type="success" class="btn btn-sm btn-success mt-3">Добавить</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body bg-white" >
            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#id</th>
                            <th>Приоритетность</th>
                            <th>Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($style as $item)
                        <tr>
                            <td>{{$item['id']}}</td>
                            <td>{{$item['name']}}</td>
                            <td>
                                <div class="form-group col-12 d-flex justify-content-between destroy">
                                    <a href="{{route('add_option_style.destroy',['style' => $item['id']])}}"
                                       class="btn btn-sm btn-danger"><i
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

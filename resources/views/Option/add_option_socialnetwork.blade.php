@extends('layout.markup')

@section('content')
<h2>Добавление социальной сети</h2>
    <div>
        <div class="shadow border p-4 my-3 bg-white">
                <form action="{{route('add_option_socialnetwork.store')}}" method="POST">
                    @csrf
                    <div class="mb-3 col-6 col-md-4">
                        <label for="" class="form-label mb-3">Добавить новую социальную сеть</label>
                        <input type="text" class="form-control form-control " name="add_new_socialnetwork">
                        <button type="success" class="btn btn-sm btn-success mt-3">Добавить</button>
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
                            <th>Название социальной сети</th>
                            <th>Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($socialnetwork as $item)
                        <tr>
                            <td>{{$item['id']}}</td>
                            <td>{{$item['name']}}</td>
                            <td>
                                @if(count($item['isUse']) == 0)
                                    <div class="form-group col-12 d-flex justify-content-between destroy">
                                        <a href="{{route('add_option_socialnetwork.destroy',['socialnetwork' => $item['id']])}}"
                                        class="btn btn-sm btn-danger"><i
                                                class="fas fa-minus"></i></a>
                                    </div>
                                @else
                                    недоступно
                                @endif

                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

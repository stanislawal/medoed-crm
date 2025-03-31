@extends('layout.markup')

@section('content')
    <h2>Список статусов специалиста</h2>
    <div>
        <div class="shadow border p-4 my-3 bg-white">
            <form action="{{route('lid-specialist-status.store')}}" method="POST">
                @csrf
                <div class="mb-3 col-6 col-md-4">
                    <label for="" class="form-label mb-3">Добавить</label>
                    <div class="mb-3">
                        <input type="text" class="form-control form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <input type="color" name="color">
                    </div>
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
                    <th>Цвет</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list as $item)
                    <tr>
                        <td>{{$item['id']}}</td>
                        <td>{{$item['name']}}</td>
                        <td>@if(!empty($item['color']))
                                <input type="color" value="{{ $item['color'] }}">
                            @else
                                -
                            @endif</td>
                        <td>
                            @if($item->lids->isEmpty())
                                <form action="{{route('lid-specialist-status.destroy', ['lid_specialist_status' => $item['id']])}}"
                                      method="post">
                                    @csrf @method('delete')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-minus"></i></button>
                                </form>
                            @else
                                Недоступно
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

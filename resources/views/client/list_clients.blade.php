@extends('layout.markup')

@section('content')

    <div class="row p-0s">
        <div class="col-12">
            <div class="w-100 shadow border rounded p-3 mb-3">
                <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                        class="fa fa-search search-icon mr-2"></i>Поиск
                </div>
                <form action="{{ route('client.index') }}" method="GET" class="check__field">
                    @csrf
                    <div class="row m-0" id="search" @if(empty(request()->all())) style="display: none;" @endif>
                        <div class="w-100 row m-0 py-3">
                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label for="" class="form-label">ID</label>
                                <input type="text" class="form-control" name="id" value="{{ request()->id ?? '' }}">
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label for="" class="form-label">Имя</label>
                                <input type="text" class="form-control" name="name" value="{{ request()->name ?? '' }}">
                            </div>
                        </div>
                        <div class="col-12 p-0">
                            <div class="form-group col-12">
                                <div class="w-100 d-flex justify-content-end">
                                    @if(!empty(request()->all() && count(request()->all())) > 0)
                                        <a href="{{ route('client.index') }}" class="btn btn-sm btn-danger mr-3">Сбросить
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
                        <h4 class="card-title">Заказчики</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables"
                               class="display table  table-hover table-head-bg-info">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Имя</th>
                                <th>Проект</th>
                                <th>Сфера деятельности</th>
                                <th>Имя компании</th>
                                <th>Контактная инф.</th>
                                <th>Ссылка на соц.сеть</th>
                                <th>Удалить</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>
                                        <a href="{{route('client.edit',['client'=> $client['id']])}}">Открыть</a>
                                    </td>
                                    <td>{{$client['name'] ?? '-'}}</td>
                                    <td>
                                        @foreach($client['project_clients'] as $item)
                                            <strong>·</strong> {{ $item['project_name'] }} <br>
                                        @endforeach
                                    </td>
                                    <td>{{$client['scope_work'] ?? '-'}}</td>
                                    <td>{{$client['company_name'] ?? '-'}}</td>
                                    <td>{{$client['contact_info'] ?? '-' }}</td>
                                    <td>{{$client['link_socialnetwork'] ?? '-' }}</td>
                                    <td>
                                        <div class="form-group col-12 d-flex justify-content-between destroy">
                                            <a href="{{route('client.destroy',['client' => $client['id']])}}"
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
    <script>   window.confirmDelete = function () {
            var res = confirm('Вы действительно хотите удалить пользователя?')
            if (!res) {
                event.preventDefault();
            }
        }</script>
@endsection

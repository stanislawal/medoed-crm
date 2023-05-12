@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <h2>
        Взаиморасчет с заказчиком:
        @foreach($clients as $client)
            {{ $client['name'] }}
        @endforeach
    </h2>


    <div class="mb-2">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ collect($report)->sum('amount') }}</strong></div>
                            <div class="text-12 nowrap-dot">Оплата:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ collect($report)->sum('count_operation') }}</strong></div>
                            <div class="text-12 nowrap-dot">Кол-во заявок оплаты:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ collect($report)->sum('price_client') }}</strong></div>
                            <div class="text-12 nowrap-dot">Цена проекта:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-100 shadow border rounded">
        <div class="card mb-0">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Информация о статьях проекта</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables"
                           class="display table table-hover table-head-bg-info table-center table-cut">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Проект</th>
                            <th>Статья</th>
                            <th>Цена заказчика</th>
                            <th>Цена автора</th>
                            <th>Оплата</th>
                            <th>Операции</th>
                            <th>Долг</th>
                            <th>Автор</th>
                            <th>Дата сдачи</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($report as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['project_name'] }}</td>
                                <td>{{ $item['article_name'] }}</td>
                                <td>{{ $item['price_client'] + 0 }}</td>
                                <td>{{ $item['price_author'] + 0 }}</td>
                                <td>{{ $item['amount'] + 0 }}</td>
                                <td>{{ $item['count_operation'] }}</td>
                                <td>
                                    @if($item['duty'] < 0)
                                        <span class="text-danger">{{ $item['duty'] + 0 }}</span>
                                    @else
                                        {{ $item['duty'] + 0 }}
                                    @endif
                                </td>
                                <td>
                                    @forelse($item['article_author'] as $author)
                                        <div class="badge bg-primary">{{ $author['full_name'] }}</div>
                                    @empty
                                        -
                                    @endforelse
                                </td>
                                <td>{{ $item['end_date_project'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

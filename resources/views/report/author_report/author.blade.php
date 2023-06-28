@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <div class="mb-3">
        @include('Answer.custom_response')
        @include('Answer.validator_response')
        <div class="w-100 shadow border rounded p-3">

            <form action="">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3">
                        <input class="form-control form-control-sm" type="month" name="month" value="{{ request()->month ?? now()->format('Y-m') }}">
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <button class="btn btn-sm btn-success">Загрузить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-2">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ $articles->sum('without_space_author') }}</strong></div>
                            <div class="text-12 nowrap-dot">Общий объем збп:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{ $articles->sum('price') }}</strong></div>
                            <div class="text-12 nowrap-dot">Гонорар:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>0</strong></div>
                            <div class="text-12 nowrap-dot">Выплачено:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>0</strong></div>
                            <div class="text-12 nowrap-dot">Долг:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ $articles->sum('price_article') }}</strong></div>
                            <div class="text-12 nowrap-dot">Общий ВД:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ $articles->sum('margin') }}</strong></div>
                            <div class="text-12 nowrap-dot">Маржа:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ $user['bank'] ?? '-' }}</strong></div>
                            <div class="text-12 nowrap-dot">Банк:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ $user['payment'] }}</strong></div>
                            <div class="text-12 nowrap-dot">Счет:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--    ТАБЛИЦА--}}
        <div class="w-100 shadow border rounded">
            <div class=>
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title"><strong>{{ $user['full_name'] }}</strong></h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables"
                               class="display table table-hover table-head-bg-info table-center table-cut">
                            <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Проект</th>
                                <th>Статья</th>
                                <th>Объем</th>
                                <th>Цена</th>
                                <th>Сумма</th>
                                <th>Оплата</th>
                                <th>Дата оплаты</th>
                                <th>Цена заказчика</th>
                                <th>Стоимость проекта</th>
                                <th>Маржа</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($articles as $article)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($article['created_at'])->format('d.m.Y') }}</td>
                                    <td>{{ $article['project_name'] }}</td>
                                    <td>{{ $article['article'] }}</td>
                                    <td>{{ $article['without_space_author']+0 }} / {{ $article['without_space_all']+0 }}
                                        ({{ $article['count_authors'] }})
                                    </td>
                                    <td>{{ $article['price_author']+0 }}</td>
                                    <td>{{ $article['price']+0 }}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>{{ $article['price_client']+0 }}</td>
                                    <td>{{ $article['price_article']+0 }}</td>
                                    <td>{{ $article['margin']+0 }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('custom_js')
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="{{asset('js/select2.js')}}"></script>
            <script src="{{asset('js/project.js')}}"></script>
@endsection

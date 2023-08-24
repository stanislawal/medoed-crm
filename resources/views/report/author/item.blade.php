@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('title')
    Свод по автору {{ $user['full_name'] }}
@endsection

@section('content')
    <div class="mb-3">
        @include('Answer.custom_response')
        @include('Answer.validator_response')
        <div class="w-100 shadow border rounded p-3">
            <form action="" class="check__field">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3">
                        <input class="form-control form-control-sm" type="month" name="month"
                               value="{{ request()->month ?? now()->format('Y-m') }}">
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
                            <div class="text-24">
                                <strong>{{number_format($indicators['without_space_author'], 2, '.', ' ')  }}</strong>
                            </div>
                            <div class="text-12 nowrap-dot">Общий объем збп:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['price'], 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Гонорар:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{number_format($indicators['payment_amount'], 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Выплачено:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{number_format($indicators['duty'] + $user['duty'] + $remainderDuty, 2, '.', ' ' ) }}</strong></div>
                            <div class="text-12 nowrap-dot">Долг:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['price_article'], 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Общий ВД:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-4 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['margin'], 2, '.', ' ')  }}</strong></div>
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
                                <strong>{{ $user['payment'] ?? "-" }}</strong></div>
                            <div class="text-12 nowrap-dot">Счет:</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-flush mb-2 border bg-white round" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        <strong class="text-14 text-danger">Списанные статьи ({{ count($ignoreArticleList) }})</strong>
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-cut" id="basic-datatables">
                                <thead>
                                <tr>
                                    <th>ID</th>
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
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($ignoreArticleList as $item)
                                    <tr>
                                        <td>{{ $item['id'] }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item['created_at'])->format('d.m.Y') }}</td>
                                        <td>{{ $item['article_project']['project_name'] }}</td>
                                        <td>{{ $item['article'] }}</td>
                                        <td>{{ number_format($item['without_space']+0, 2, '.', ' ')}}</td>
                                        <td>{{ number_format($item['price_author']+0, 2, '.', ' ') }}</td>
                                        <td>{{ number_format($item['price']+0, 2, '.', ' ') }}</td>
                                        <td>{{ number_format($item['payment_amount']+0, 2, '.', ' ') }}</td>
                                        <td>{{ $item['payment_date'] ?? '-' }}</td>
                                        <td>{{number_format($item['price_client']+0, 2, '.', ' ')  }}</td>
                                        <td>{{number_format($item['price_article']+0, 2, '.', ' ')  }}</td>
                                        <td>{{number_format($item['margin']+0, 2, '.', ' ')  }}</td>
                                        <td><a href="{{ route('change_ignore_article', ['id' => $item['id'],'ignore' => false]) }}" class="btn btn-sm btn-success from_ignore">Из списания</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-gray">Нет статей в списании</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
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
                        <div>Всего записей: <strong>{{ $articles->total() }}</strong></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="w-100 d-flex justify-content-center mb-3">
                        {{ $articles->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                    <div class="table-responsive">
                        <table id="basic-datatables"
                               class="display table table-hover table-head-bg-info table-center table-cut">
                            <thead>
                            <tr>
                                <th>ID</th>
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
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($articles as $article)
                                <tr>
                                    <td>{{ $article['id'] }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($article['created_at'])->format('d.m.Y') }}</td>
                                    <td>{{ $article['project_name'] }}</td>
                                    <td>{{ $article['article'] }}</td>
                                    <td class="nowrap">{{number_format($article['without_space_all']+0, 2, '.', ' ')  }}</td>
                                    <td>{{number_format($article['price_author']+0, 2, '.', ' ')  }}</td>
                                    <td>{{number_format($article['price']+0, 2, '.', ' ')  }}</td>
                                    <td class="bg-grey2">
                                        <div>
                                            <input type="number" step="0.01" style="width: 70px;" class="min-input" name="payment_amount"
                                                   onchange="updateData(this, '{{ route('article.update', ['article' => $article['article_id']]) }}')"
                                                   value="{{ $article['payment_amount'] ?? 0 }}">
                                        </div>
                                    </td>
                                    <td class="bg-grey2">
                                        <div>
                                            <input type="date" style="width: 100px;" class="min-input" name="payment_date"
                                                   onchange="updateData(this, '{{ route('article.update', ['article' => $article['article_id']]) }}')"
                                                   value="{{ $article['payment_date'] ?? null }}">
                                        </div>
                                    </td>
                                    <td>{{number_format($article['price_client']+0, 2, '.', ' ')  }}</td>
                                    <td>{{number_format($article['price_article']+0, 2, '.', ' ')  }}</td>
                                    <td>{{number_format($article['margin']+0, 2, '.', ' ')  }}</td>
                                    <td><a href="{{ route('change_ignore_article', ['id' => $article['id'],'ignore' => true]) }}" class="btn btn-sm btn-danger to_ignore">Списать</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100 d-flex justify-content-center mt-3">
                        {{ $articles->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="{{ asset('js/author.js') }}"></script>
    <script>
        $('.to_ignore').click(function(){
            var res = confirm('Вы действительно хотите перенести статью в списание?')
            if (!res) {
                event.preventDefault();
            }
        });

        $('.from_ignore').click(function(){
            var res = confirm('Вы действительно хотите убрать статью из списания?')
            if (!res) {
                event.preventDefault();
            }
        });
    </script>
{{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
{{--    <script src="{{asset('js/select2.js')}}"></script>--}}
{{--    <script src="{{asset('js/project.js')}}"></script>--}}
@endsection

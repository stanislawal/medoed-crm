@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div>
        <div>
            @include('Answer.custom_response')
            @include('Answer.validator_response')
            <div class="w-100 shadow border rounded bg-white p-3 mb-3">
                <div class="btn btn-sm btn-secondary" onclick="searchToggle()"><i
                        class="fa fa-search search-icon mr-2"></i>Поиск
                </div>
                <form action="{{ route('article.index') }}" method="GET" class="check__field">
                    @csrf
                    <div class="row m-0" id="search">
                        <div class="w-100 row m-0 py-3">
                            @if(\App\Helpers\UserHelper::isManager())
                                <div class="form-group col-12 col-md-6 col-lg-4">
                                    <label class="form-label">Менеджер</label>
                                    <select class="form-control form-control-sm">
                                        <option>{{ auth()->user()->full_name }}</option>
                                    </select>
                                </div>
                            @else
                                <div class="form-group col-12 col-md-6 col-lg-4">
                                    <label for="" class="form-label">Менеджер</label>
                                    <select class="form-control form-control-sm" name="manager_id">
                                        <option value="">Не выбрано</option>
                                        @foreach ($managers as $manager)
                                            <option @if($manager['id'] == request()->manager_id) selected
                                                    @endif value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label class="form-label">Диапазон добавления</label>
                                <div class="input-group">
                                    <input type="date" name="date_from" class="form-control form-control-sm"
                                           value="{{ request()->date_from ?? \Carbon\Carbon::parse(now())->startOfMonth()->format('Y-m-d') }}"
                                           required>
                                    <input type="date" name="date_before" class="form-control form-control-sm"
                                           value="{{ request()->date_before ?? \Carbon\Carbon::parse(now())->endOfMonth()->format('Y-m-d') }}"
                                           required>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label class="form-label">Дата</label>
                                <div class="input-group">
                                    <input type="date" name="date_article" class="form-control form-control-sm"
                                           value="{{ request()->date_article }}"
                                    >
                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label class="form-label">Статья</label>
                                <input type="text" name="article" class="form-control form-control-sm"
                                       value="{{ request()->article ?? "" }}">
                            </div>

                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label class="form-label">Авторы</label>
                                <select class="form-select form-select-sm select-2" multiple
                                        name="author_id[]">
                                    @foreach($authors as $author)
                                        <option value="{{$author['id']}}"
                                                @if (in_array($author['id'], request()->author_id ?? []))
                                                    selected
                                            @endif>
                                            {{$author['full_name'] ?? ''}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-6 col-lg-4">
                                <label class="form-label">Проект</label>
                                <select class="form-select form-select-sm select-2"
                                        name="project_id">
                                    <option value="">Не выбрано</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project['id'] }}"

                                                @if($project['id'] == request()->project_id ?? '') selected @endif>
                                            {{ $project['project_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <div class="w-100 d-flex justify-content-end">
                                    <button class="btn btn-sm btn-success">Искать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="mb-2">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                            <div class="px-3 py-2 shadow border bg-white rounded">
                                <div class="text-24"><strong>{{ $statistics['count_days_in_range'] }}</strong></div>
                                <div class="text-12 nowrap-dot">Дней в месяце:</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                            <div class="px-3 py-2 shadow border bg-white rounded">
                                <div class="text-24"><strong>{{ $statistics['current_day_in_range'] }}</strong></div>
                                <div class="text-12 nowrap-dot">Текущий день месяца:</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                            <div class="px-3 py-2 shadow border bg-white rounded">
                                <div class="text-24"><strong>{{ $statistics['expectation'] }}</strong></div>
                                <div class="text-12 nowrap-dot">Ожидаемый объем ЗБП:</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                            <div class="px-3 py-2 shadow border bg-white rounded">
                                <div class="text-24"><strong>{{ $statistics['passed'] }}</strong></div>
                                <div class="text-12 nowrap-dot">Сдано за сегодня:</div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                            <div class="px-3 py-2 shadow border bg-white rounded">
                                <div class="text-24"><strong>{{ $statistics['sum_without_space'] }}</strong></div>
                                <div class="text-12 nowrap-dot">Всего ЗБП:</div>
                            </div>
                        </div>

                        @role('Admin')
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                            <div class="px-3 py-2 shadow border bg-white rounded">
                                <div class="text-24"><strong>{{ (int)$statistics['sum_gross_income'] }}</strong></div>
                                <div class="text-12 nowrap-dot">Валовый доход (сумма):</div>
                            </div>
                        </div>
                        @endrole

                        @if(\App\Helpers\UserHelper::isManager() || !is_null(request()->manager_id))
                            <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-2">
                                <div class="px-3 py-2 shadow border bg-white rounded">
                                    <div class="text-24"><strong>{{ $statistics['manager_salary'] }}</strong></div>
                                    <div class="text-12 nowrap-dot">Расчет менеджера:</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="w-100">
            <div class="card shadow border bg-white rounded">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title ">Редактирование статей</h4>
                        <div class="text-16">Найдено записей: {{ $articles->total() }}</div>
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
                                <th></th>
                                {{--                                <th>ID</th>--}}
                                <th style="min-width: 200px;">Проект</th>
                                @role('Администратор')
                                <th style="min-width: 150px;">Заказчик(и)</th>
                                @endrole
                                <th style="min-width: 200px;">Статья</th>
                                <th style="min-width: 100px;">ЗБП</th>
                                <th style="min-width: 100px;">Цена заказчика</th>
                                <th style="min-width: 100px;">Валюта</th>
                                @unlessrole ('Менеджер')
                                <th style="min-width: 150px;">Менеджер</th>
                                @endunlessrole

                                @role('Администратор')
                                <th style="min-width: 100px;">ВД</th>@endrole
                                <th style="min-width: 150px;">Автор</th>
                                <th style="min-width: 100px;">Цена автора</th>
                                <th style="min-width: 150px;">Редактор</th>
                                <th style="min-width: 100px;">Цена редактора</th>
                                <th style="min-width: 200px;">Ссылка на текст</th>
                                <th>Дата создания</th>
                                @role('Администратор')
                                <th>Удалить</th>
                                @endrole


                                <th>Действие</th>
                            </tr>
                            <style>
                                .form-control {
                                    color: black;
                                }

                            </style>
                            </thead>
                            <tbody>
                            @foreach($articles as $article)
                                <tr class="row_{{ $article['id'] }}"
                                    data-url="{{ route('article.update', ['article' => $article['id']]) }}">
                                    <td><input type="checkbox" name="check"
                                               @if((bool)$article['check']) checked @endif
                                        >
                                    </td>
                                    {{--                                    <td>{{ $article['id'] }}</td>--}}
                                    {{--Имя проекта--}}
                                    <td>
                                        <div>
                                            <select class="form-select form-select-sm select-2"
                                                    data-class="row_{{ $article['id'] }}" name="project_id">
                                                <option value="" data-author="" data-client="">Не выбрано</option>
                                                @foreach($projects as $project)
                                                    <option value="{{ $project['id'] }}"
                                                            data-author="@foreach($project['project_author'] as $author){{  $author['full_name']}} @endforeach"
                                                            data-client="@foreach($project['project_clients'] as $author){{  $author['name']}} @endforeach"
                                                            @if($project['id'] == $article['project_id']) selected @endif>
                                                        {{ $project['project_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{--                                            @dd($article)--}}
                                        </div>
                                    </td>
                                    @role('Администратор')
                                    <td class="td-client">
                                        {{--                                        Заказчик--}}

                                        @foreach($article['articleProject']['projectClients'] ?? [] as $client)
                                            {{$client['name'] ?? ''}}
                                        @endforeach
                                    </td>
                                    @endrole

                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{--                                            Название статьи--}}
                                            <input class="form-control form-control-sm" name="article"
                                                   value="{{$article['article'] ?? ''}}">
                                        </div>
                                    </td>
                                    {{--                                    ЗБП--}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input class="form-control form-control-sm" name="without_space"
                                                   value="{{$article['without_space'] ?? ''}}">
                                        </div>
                                    </td>
                                    {{--                                    Цена заказчика--}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input class="form-control form-control-sm" name="price_client"
                                                   value="{{$article['price_client'] ?? ''}}"></div>
                                    </td>
                                    {{--                                    Валюта--}}
                                    <td>
                                        <div>
                                            <select class="form-select form-select-sm" name="id_currency">
                                                @foreach($currency as $item)
                                                    <option value="{{$item['id']}}"
                                                            @if($item['id'] == $article['id_currency']) selected @endif>{{$item['currency']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                    @unlessrole('Менеджер')
                                    <td>

                                        <div>
                                            <select class="form-select form-select-sm select-2"
                                                    data-class="row_{{ $article['id'] }}" name="manager_id">
                                                <option value="" data-author="" data-client="">Не выбрано</option>
                                                @foreach($managers as $manager)
                                                    <option value="{{ $manager['id'] }}"
                                                            @if($manager['id'] == ($article['articleManager']['id'] ?? "")) selected @endif>
                                                        {{ $manager['full_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    @endunlessrole


                                    {{--                                    ВАЛОВЫЙ ДОХОД--}}
                                    @role('Администратор')
                                    <td>
                                        {{$article['gross_income'] + 0 ?? ''}}
                                    </td>
                                    @endrole


                                    <td class="td-author">
                                        {{--                                        Автор--}}
                                        <select class="form-select form-select-sm select-2"
                                                name="select_authors">

                                            @foreach($authors as $author)
                                                <option value="{{$author['id']}}"
                                                        @if(in_array($author['id'], collect($article['articleAuthor'])->pluck('id')->toArray()))
                                                            selected
                                                    @endif>

                                                    {{$author['full_name'] ?? ''}}
                                                </option>

                                            @endforeach
                                        </select>

                                    </td>
                                    {{--                                    Цена автора--}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input class="form-control form-control-sm" name="price_author"
                                                   value="{{$article['price_author'] ?? ''}}">
                                        </div>
                                    </td>


                                    <td class="td-author">
                                        {{--                                        Редактор--}}
                                        <select class="form-select form-select-sm select-2" multiple
                                                name="select_redactors[]">

                                            @foreach($authors as $author)
                                                <option value="{{$author['id']}}"
                                                        @if(in_array($author['id'], collect($article['articleRedactor'])->pluck('id')->toArray()))
                                                            selected
                                                    @endif>

                                                    {{$author['full_name'] ?? ''}}
                                                </option>

                                            @endforeach
                                        </select>

                                    </td>
                                    {{--                                    Цена редактора--}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input class="form-control form-control-sm" name="price_redactor"
                                                   value="{{$article['price_redactor'] ?? ''}}">
                                        </div>
                                    </td>

                                    {{--Ссылка--}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input class="form-control form-control-sm" type="url" name="link_text"
                                                   value="{{$article['link_text'] ?? ''}}">
                                            <a class="ml-2" target="_blank" href="{{$article['link_text'] ?? ''}}"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($article['created_at'])->format('Y-m-d H:i') }}</td>
                                    @role('Администратор')
                                    <td>
                                        <div class="form-group col-12 d-flex justify-content-between destroy">
                                            <a href="{{route('article.destroy', ['article' => $article['id']])}}"
                                               class="btn btn-sm btn-outline-danger" onclick="confirmDelete()"><i
                                                    class="fas fa-minus"></i></a>
                                        </div>
                                    </td>
                                    @endrole
                                    <td>
                                        {{-- <div class="btn btn-sm btn-primary edit"
                                             onclick="edit('row_{{ $article['id'] }}')">
                                            <i class="fas fa-pen"></i>
                                        </div> --}}
                                        <div class="btn btn-sm btn-success save"
                                             onclick="save('row_{{ $article['id'] }}')">
                                            <i class="fas fa-save"></i>
                                        </div>
                                    </td>

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
    <script
        src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script src="{{asset('js/article.js')}}"></script>
    <script>
        window.confirmDelete = function () {
            var res = confirm('Вы действительно хотите удалить этот проект?')
            if (!res) {
                event.preventDefault();
            }
        }
    </script>
@endsection

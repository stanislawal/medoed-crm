@extends('layout.markup')

@section('title')
    Свод по авторам
@endsection

@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="mb-3">
        @include('Answer.custom_response')
        @include('Answer.validator_response')
        <div class="w-100 shadow border rounded p-3 bg-white">
            <form action="" class="check__field">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3 mb-3">
                        <label class="form-label" for="">Дата</label>
                        <input class="form-control form-control-sm" type="month" name="month"
                               value="{{ request()->month ?? "" }}">
                    </div>

                    <div class="col-12 col-md-4 col-lg-3 mb-3">
                        <label class="form-label" for="">Промежуток</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control form-control-sm" placeholder="От"
                                   value="{{ request()->start_date ?? '' }}">
                            <input type="date" name="end_date" class="form-control form-control-sm" placeholder="До"
                                   value="{{ request()->end_date ?? '' }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-4 col-lg-3 mb-3">
                        @if(\App\Helpers\UserHelper::isAuthor())
                            <label class="form-label">Автор</label>
                            <select class="form-control form-control-sm">
                                <option>{{ auth()->user()->full_name }}</option>
                            </select>
                        @else
                            <label class="form-label" for="">Автор</label>
                            <select class="form-select form-select-sm select-2"
                                    name="author_id">
                                <option value="">Не выбрано</option>
                                @foreach($authors as $author)
                                    <option value="{{$author['id']}}"
                                            @if ($author['id'] == request()->author_id ?? null)
                                                selected
                                        @endif>
                                        {{$author['full_name'] ?? ''}}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="col-12 col-md-4 col-lg-3 mb-3">
                        <label class="form-label" for="">Банк</label>
                        <select class="form-select form-select-sm select-2"
                                name="bank_id">
                            <option value="">Не выбрано</option>
                            @foreach($banks as $bank)
                                <option value="{{$bank['id']}}" @if(request()->bank_id == $bank['id']) selected @endif>
                                    {{$bank['name'] ?? ''}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4 col-lg-3 mb-3">
                        <label class="form-label" for="">Статус работы</label>
                        <select class="form-select form-select-sm" name="status_work">
                            <option value="">Не выбрано</option>
                            <option value="1" @if(request()->status_work == '1') selected @endif>Только работающие
                            </option>
                            <option value="0" @if(request()->status_work == '0') selected @endif>Только не работающие
                            </option>
                        </select>
                    </div>

                    <div class="col-12">
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
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['margin'], 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Маржа:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24"><strong>{{$diffInWeekdays}} / {{$diffInCurrentDay}}</strong></div>
                            <div class="text-12 nowrap-dot">Количество рабочих дней / Текущий день:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['without_space'], 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Общий объем збп:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['amount'], 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Общая сумма гонораров:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['duty'] + $remainderDuty->sum('remainder_duty'), 2, '.', ' ')  }}</strong>
                            </div>
                            <div class="text-12 nowrap-dot">Итого к выплате:</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['gross_income'], 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Валовый доход:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($indicators['payment_amount'], 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Выплачено:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{number_format($remainderDuty->sum('remainder_duty'), 2, '.', ' ')  }}</strong></div>
                            <div class="text-12 nowrap-dot">Переносящийся долг:</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ $indicators['working_day'] }}</strong></div>
                            <div class="text-12 nowrap-dot">Раб. день</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-2">
                        <div class="px-3 py-2 shadow border bg-white rounded">
                            <div class="text-24">
                                <strong>{{ number_format($indicators['without_space_in_day'], 2, '.', ' ') }}</strong></div>
                            <div class="text-12 nowrap-dot">Факт V/раб. день</div>
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="col-12 col-md-3">--}}
{{--                <div class="w-100">--}}
{{--                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <div class="d-flex align-items-center">--}}
{{--                                <div style="font-size: 40px">$</div>--}}
{{--                                <div class="pl-3"><span class="text-14">USD:</span></div>--}}
{{--                                <div class="pl-2 text-18">{{$rates->where('id_currency', 2)->first()->rate ?? ""}}</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <div style="font-size: 40px">€</div>--}}
{{--                            <div class="pl-3"><span class="text-14">EUR:</span></div>--}}
{{--                            <div class="pl-2 text-18">{{$rates->where('id_currency', 3)->first()->rate ?? ""}}</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <div style="font-size: 38px">₴</div>--}}
{{--                            <div class="pl-3"><span class="text-14">UAH:</span></div>--}}
{{--                            <div class="pl-2 text-18">{{$rates->where('id_currency', 4)->first()->rate ?? ""}}</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>

    {{--    ТАБЛИЦА--}}
    <div class="w-100 shadow border rounded bg-white">
        <div class=>
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Общий свод по авторам</h4>
                    <div>Всего записей: <strong>{{ $reports->total() }}</strong></div>
                </div>
            </div>
            <div class="card-body">
                <div class="w-100 d-flex justify-content-center mb-3">
                    {{ $reports->appends(request()->input())->links('vendor.pagination.custom')  }}
                </div>
                {{--                @dd($authors)--}}
                <div class="table-responsive">
                    <table id="basic-datatables"
                           class="display table table-hover table-head-bg-info table-center table-cut">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="sort-p">@include('components.table.sort', ['title' => 'Банк', 'column'       => 'bank', 'routeName' => 'report_author.index'])</th>
                            <th>К выплате</th>
                            <th class="sort-p">@include('components.table.sort', ['title' => 'Автор', 'column'       => 'full_name', 'routeName' => 'report_author.index'])</th>
                            <th class="sort-p">@include('components.table.sort', ['title' => 'Объем', 'column'       => 'without_space', 'routeName' => 'report_author.index'])</th>
                            <th class="sort-p">@include('components.table.sort', ['title' => 'Гонорар', 'column'     => 'amount', 'routeName' => 'report_author.index'])</th>

                            <th class="sort-p">@include('components.table.sort', ['title' => 'ВД', 'column'          => 'gross_income', 'routeName' => 'report_author.index'])</th>
                            <th class="sort-p">@include('components.table.sort', ['title' => 'Маржа', 'column'       => 'margin', 'routeName' => 'report_author.index'])</th>
                            <th>Ср. цена</th>
                            <th style="background-color: rgba(106,111,113,0.7)!important;">Раб. день</th>
                            <th style="background-color: rgba(106,111,113,0.7)!important; color: rgba(56,65,244,0.91)!important">
                                Факт V/раб. день
                            </th>
                            <th style="background-color: rgba(0,0,0,0.7)!important; color: orange!important;">
                                Недозагрузка
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($reports as $author)
                            <tr>
                                <td>
                                    <a href="{{ route('report_author.show', ['report_author' => $author['id'], 'month' => request()->month ?? now()->format('Y-m')]) }}">
                                        <i class="fas fa-grip-horizontal"></i>
                                    </a>
                                </td>
                                <td>{{ $author['bank'] ?? '-' }}</td>
                                <td class="text-danger nowrap">{{number_format($author['duty'] + ($remainderDuty->where('author_id', $author['id'])->first()['remainder_duty'] ?? 0), 2, '.', ' ')  }}</td>
                                <td class="nowrap" @if(!$author['count_payment']) style="background-color: #ff00000f; color: red;" @endif title="Не было оплат более 30 дней">
                                    {{ $author['full_name'] ?? '-' }}
                                    @if(!$author['count_payment'])
                                        <i class="ms-2 fas fa-credit-card"></i>
                                    @endif
                                </td>
                                <td class="nowrap">{{number_format($author['without_space']+0, 2, '.', ' ')  }}</td>
                                <td class="nowrap">{{number_format($author['amount']+0, 2, '.', ' ')  }}</td>
                                <td class="nowrap">{{number_format($author['gross_income']+0, 2, '.', ' ')  }}</td>
                                <td class="nowrap">{{number_format($author['margin']+0, 2, '.', ' ')  }}</td>
                                <td class="nowrap">{{number_format($author['avg_price']+0, 2, '.', ' ') }}</td>
                                <td class="nowrap" style="background-color: rgba(106,111,113,0.7)!important;">{{$author['working_day']}}</td>
                                <td class="nowrap" style="background-color: rgba(106,111,113,0.7)!important; color: rgb(255,255,255)!important">{{number_format(($author['is_work'] ? $author['without_space'] : 0) / $diffInCurrentDay, 2, '.', ' ') }}</td>
                                <td class="nowrap" style="background-color: rgba(255,165,0,0.91)!important; color: black!important;">{{number_format(($author['without_space'] / $diffInCurrentDay) - $author['working_day'], 2, '.', ' ') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="w-100 d-flex justify-content-center mt-3">
                    {{ $reports->appends(request()->input())->links('vendor.pagination.custom')  }}
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

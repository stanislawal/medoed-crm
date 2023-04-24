@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')
    {{--    @dd($rates->where('id_currency', 3)->first()->rate ?? "");--}}
    <div>
        <div class="col-12 col-md-3">
            <h3>Курс валют:</h3>
            <form action="{{route('rate.update')}}" method="post">
                @csrf
                <div class="w-100">
                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <div style="font-size: 40px">$</div>
                                <div class="pl-3"><span class="text-14">USD:</span></div>
                                <div class="ml-3"></div>
                                <div class="pl-2 text-18">
                                    <input name="usd"
                                           type="number" step="0.01"
                                           placeholder="новый курс"
                                           class="form-control border border-primary form-control-sm"
                                           style="max-width: 100px;"
                                           value="{{ $rates->where('id_currency', 2)->first()->rate ?? ""  }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 40px">€</div>
                            <div class="pl-3"><span class="text-14">EUR:</span></div>
                            <div class="ml-3"></div>
                            <div class="pl-2 text-18">
                                <input name="eur"
                                       type="number" step="0.01"
                                       placeholder="новый курс"
                                       class="form-control border border-primary form-control-sm"
                                       style="max-width: 100px;"
                                       value="{{ $rates->where('id_currency', 3)->first()->rate ?? "" }}">
                            </div>
                        </div>
                    </div>

                    <div class="px-3 py-1 shadow border bg-white rounded mb-2">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 40px">₴</div>
                            <div class="pl-3"><span class="text-14">UAH:</span></div>
                            <div class="ml-3"></div>
                            <div class="pl-2 text-18">
                                <input name="uah"
                                       type="number" step="0.01"
                                       placeholder="новый курс"
                                       class="form-control border border-primary form-control-sm"
                                       style="max-width: 100px;"
                                       value="{{ $rates->where('id_currency', 4)->first()->rate ?? "" }}">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-sm btn-success">Сохранить</button>
            </form>
        </div>
    </div>
@endsection

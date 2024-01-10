@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')

    <h2 class="mb-3">Редактирование заказчика</h2>


    <div class="row m-0">
        <div class="col-lg-9 p-0">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>
    </div>
    {{--    @dd($clients)--}}
    <form action="{{route('client.update', ['client' => $clients['id']])}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row m-0">
            <div class="col-12">
                <div class="shadow border rounded row mb-3">
                    <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">О клиенте
                    </div>


                    <div class="form-group col-12 col-lg-6">
                        <label for="" class="form-label">Контактное лицо</label>
                        <input type="text" value="{{$clients['name']}}" class="form-control form-control-sm" name="name">
                    </div>

                    <div class="form-group col-12 col-lg-6">
                        <label for="" class="form-label">Сфера деятельности</label>
                        <input type="text" value="{{$clients['scope_work']}}" class="form-control form-control-sm" name="scope_work">
                    </div>

                    <div class="form-group col-12 col-lg-6">
                        <label for="" class="form-label">Название компании</label>
                        <input type="text" value="{{$clients['company_name']}}" class="form-control form-control-sm"
                               name="company_name">
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="" class="form-label">Сайт</label>
                        <input type="text" value="{{$clients['site']}}" class="form-control form-control-sm" name="site">
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="" class="form-label">Контактная информация</label>
                        <input type="text" value="{{$clients['contact_info']}}" class="form-control form-control-sm" name="contact_info">
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="characteristic" class="form-label">Портрет и общая хар-ка</label>
                        <textarea id="characteristic" name="characteristic" class="form-control">{{$clients['characteristic']}}</textarea>
                    </div>

                    <div class="form-group col-12 mb-2">
                        <button class="btn btn-success btn-sm mr-3 w-auto">Сохранить</button>
                    </div>
                </div>

            </div>
        </div>

    </form>

@endsection

@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{asset('js/select2.js')}}"></script>
@endsection

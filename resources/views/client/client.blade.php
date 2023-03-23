@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')

    <h2 class="mb-3">Добавить нового заказчика</h2>


    <div class="row m-0">
        <div class="col-lg-9 p-0">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>
    </div>

    <form action="{{route('client.store')}}" method="POST">
        @csrf
        <div class="row m-0">
            <div class="col-12">
                <div class="shadow border rounded row mb-3">
                    <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">О клиенте
                    </div>
                    <div class="w-100 row m-0 p-2">
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Имя заказчика</label>
                            <input type="text" class="form-control form-control-sm" name="name">
                            <label for="" class="form-label">Место ведения диалога</label>
                            <select class="form-control form-control-sm select-client-socialnetworks select-2" multiple
                                    name="dialog_location">
                                <option disabled value="">Места</option>
                                @foreach ($socialNetwork ?? '' as $dialog)
                                    <option value="{{$dialog['id']}}">{{$dialog['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label mt-2">Ссылка на соц. сеть</label>
                            <input type="text" class="form-control form-control-sm" name="link_socialnetwork">
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Сфера деятельности</label>
                            <input type="text" class="form-control form-control-sm" name="scope_work">
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Контактная информация</label>
                            <input type="text" class="form-control form-control-sm" name="contact_info">
                        </div>

                        {{--                        <div class="form-group col-12 col-lg-6">--}}
                        {{--                            <label for="" class="form-label">День рождения</label>--}}
                        {{--                            <input type="date" class="form-control" name="birthday">--}}
                        {{--                        </div>--}}

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Имя компании</label>
                            <input type="text" class="form-control form-control-sm" name="company_name">
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Сайт компании</label>
                            <input type="text" class="form-control form-control-sm" name="site">
                        </div>


                            <div class="form-group col-12 col-lg-6">
                                <label for="characteristic" class="form-label">Портрет и общая хар-ка</label>
                                <textarea id="characteristic" name="characteristic" class="form-control"> </textarea>

                        </div>
                        <div class="form-group col-12 mb-2">
                            <button class="btn btn-sm btn-success">Создать</button>
                        </div>
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

@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="row p-0s">
        <div class="col-12">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>
        <div class="row m-0">
            <div class="col-12">
                <form class="shadow border rounded row mb-3" action="{{route('article.store')}}" method="post">
                    <div class="w-100 text-18 px-3 py-2 font-weight-bold border-bottom bg-blue text-white">Создать
                        статью
                        для проекта
                    </div>
                    @csrf

                    <div class="w-100 row m-0 p-2">
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Менеджер</label>
                            <select required class="form-select form-select-sm" name="manager_id">
                                <option value="">Не выбрано</option>
                                @foreach ($managers as $manager)
                                    <option value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Назначить авторов</label>
                            <select class="form-control select-2" multiple name="author_id[]">
                                <option value="">Не выбрано</option>
                                @foreach ($authors as $author)
                                    <option value="{{$author['id']}}">{{$author['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Цена автора</label>
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm" type="number" step="0.1" min="0.1"
                                       name="price_author">
                                <div class="input-group-append input-group-sm">
                                    <span class="input-group-text" id="basic-addon2">РУБ</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Редактор</label>
                            <select class="form-control select-2" multiple name="redactor_id[]">
                                <option value="">Не выбрано</option>
                                @foreach ($authors as $author)
                                    <option value="{{$author['id']}}">{{$author['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Цена редактора</label>
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm" type="number" step="0.1" min="0.1"
                                       name="price_redactor">
                                <div class="input-group-append input-group-sm">
                                    <span class="input-group-text" id="basic-addon2">РУБ</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Статья</label>
                            <input class="form-control form-control-sm" type="text" name="article" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">ЗБП</label>
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm"  type="number" step="0.1"
                                       min="0.1"
                                       name="without_space">
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Валюта</label>
                            <div class="input-group mb-3">
                                <select  class="form-control form-control-sm" name="id_currency">
                                    <option value="1">RUB</option>
                                    @foreach ($currency ?? '' as $item)
                                        <option value="{{$item['id']}}">{{$item['currency']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Цена заказчика</label>
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm"  type="number" step="0.1"
                                       min="0.1"
                                       name="price_client">
                                <div class="input-group-append">
                                    <span class="input-group-text input-group-sm" id="basic-addon2">РУБ</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Ссылка на текст</label>
                            <input  class="form-control form-control-sm" type="text" name="link_text">
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="" class="form-label">Название проекта</label>
                            <select class="form-control border form-control-sm select-2"
                                    title="Пожалуйста, выберите"
                                    name="project_id">
                                <option value=" " selected>Не выбрано</option>
                                @foreach( $project as $project_info)
                                    <option
                                        value="{{$project_info['id']}}">{{$project_info['project_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <button class="btn btn-success btn-sm mr-3 w-auto">Создать</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
@endsection

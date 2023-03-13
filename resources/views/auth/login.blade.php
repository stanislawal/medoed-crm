@extends('layout.layout')

@section('title')
    Войти | {{ config('app.name') }}
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('./css/auth.css') }}">
@endsection

@section('html')
    <div class="container auth_container w-100 d-flex justify-content-center align-items-center">
        <form class="form-horizontal p-2 auth_form border rounded shadow" action="{{route('login.store')}}"
              method="post">
            @csrf
            <div class="row m-0">
                <div class="col-12 p-0">
                    @include('Answer.custom_response')
                    @include('Answer.validator_response')
                </div>
            </div>
            <div class="auth_title text-center text-18 p-2">АВТОРИЗАЦИЯ</div>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="login" placeholder="Логин">
            </div>
            <div class="form-group">
                <input type="password" class="form-control form-control-sm" name="password" placeholder="Пароль">
            </div>
            <div class="form-group">
                <button type="submit" class="w-100 btn btn-info btn-sm">ВХОД</button>
            </div>
        </form>
    </div><!-- /.container -->
@endsection

@section('custom_js')

@endsection

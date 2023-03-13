@extends('layout.layout')

@section('title')Страница администратора | {{ config('app.name') }}@endsection

@section('custom_css')
    <script src="{{ asset('css/auth.css') }}" > </script>
    <script src="{{asset('css/app.css')}}"></script>
@endsection

@section('content')

    <div class="one">
        <p class="colortest">color</p>
    </div>

@endsection
@section('custom_js')

@endsection

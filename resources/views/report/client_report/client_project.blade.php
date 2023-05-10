@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <h2>Взаиморасчет с заказчиком: Имя заказчика</h2>
    <div>
        <h4>Оплата проекта</h4>
        <div>
            <input class="form-control form-control-sm w-25" type="text">
        </div>
        <div class="mt-5">
            <table id="basic-datatables"
                   class="display table table-hover table-head-bg-info table-center table-cut">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Проект</th>
                    <th>Цена проекта</th>
                    <th>Статьи</th>
                    <th>Цена заказчика</th>
                    <th>Автор</th>
                    <th>Цена автора</th>
                    <th>Оплата</th>
                    <th>Дата сдачи</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

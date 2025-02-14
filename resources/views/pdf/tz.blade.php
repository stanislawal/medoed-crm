<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            max-width: 620px;
            width: 100%;
            margin: 0 auto;
            font-size: 12px;
            font-family: DejaVu Sans;
            line-height: 16px;
        }

        p {
            padding: 0;
            margin: 0;
        }

        .application {
            font-style: italic;
            text-align: right;
        }

        .title {
            text-align: center;
            font-weight: bold;
        }

        .p_1 {
            font-weight: bold;
        }

        .p_1_1 {
            padding-left: 17px;
        }

        /*table, th{*/
        /*    font-weight: bold;*/
        /*}*/

        /*table, td{*/
        /*    font-weight: normal;*/
        /*}*/

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 3px 3px 3px 5px;
            text-align: left;

        }
        .table {
            width: 100%;
            margin-top: 15px;
        }
        .sign{
            width: 100%;
            margin-top: 45px;
            height: 16px;
        }
        .sign_1{
            float:left;
        }
        .sign_2{
            float:right;
        }

        .sign_1, .sign_2{
            position: relative;
        }

        .sign_mp{
            position: absolute;
            top: 16px;
            left: 0;
        }

        .space{
            color: transparent;
        }
    </style>
</head>
<body>
<div>
    <div class="application"><p>Приложение № 2</p>
        <p>к договору № ___________</p>
        <p>от "<span class="space">____</span>"<span class="space">_______________</span>г.</p></div>
    <div class="title">Техническое задание</div>
    <div class="title" style="padding-bottom: 10px;">на услуги по написанию текстов</div>
    <div class="p_1">1. Требования к текстам</div>
    <div class="p_1_1">1.1 Тексты должны соответствовать требованиям редакционной политики, оговоренным в Приложении № 1
        Договору.
    </div>
    <div class="p_1_1">1.2 Дополнительные технические требования к текстам, объему, содержанию, оформлению, а также
        ключевые запросы оговариваются в текстовых сообщениях, направленных Заказчиком Исполнителю с использованием
        каналов связи, оговоренных в Договоре.
    </div>
    <div class="p_1">2. Перечень и объем текстов</div>
    <table class="table">
        <tbody>
        <tr>
            <th>№</th>
            <th>Название или тематика текста</th>
            <th>Объем</th>
        </tr>

        @foreach($articles as $i => $article)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $article->article }}</td>
                <td>{{ $article->without_space }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="sign">
        <div class="sign_1">Исполнитель _______________________________<div class="sign_mp">М. П.</div></div>
        <div class="sign_2">Заказчик _______________________________<div class="sign_mp">М. П.</div></div>
    </div>
</div>
</body>
</html>

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

        .title {
            text-align: center;
            font-weight: bold;
        }

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

        .sign {
            width: 100%;
            margin-top: 45px;
            height: 32px;
        }

        .sign_1 {
            float: left;
        }

        .sign_2 {
            float: right;
        }

        .sign_1, .sign_2 {
            position: relative;
        }

        .sign_mp {
            position: absolute;
            top: 16px;
            left: 0;
        }

        .space {
            color: transparent;
        }

        .sign_line {
            display: inline-block;
            position: relative;
        }

        .sign_name {
            position: absolute;
            top: -2px;
            left: 5px;
        }

        .underline {
            border-bottom: 1px solid #000;
        }

        .sign_img {
            width: 70px;
            position: absolute;
            right: 8px;
            top: -34px;
        }
    </style>
</head>
<body>
<div>
    <div class="title">Акт</div>
    <div class="title">приема-передачи оказанных услуг № <span class="underline" style="padding: 0 5px;"> {{ $uniqueNumberDocument }} </span>
    </div>
    <div class="title">от "<span class="underline">{{ $currentDate['day'] }}</span>"<span class="underline"
                                                                                                 style="padding: 0 5px;">{{ $currentDate['month'] }} {{ $currentDate['year'] }}</span>г.
    </div>
    <br>
    <br>
    <div>
        <strong>Исполнитель:</strong> {{ $author['fio_for_doc'] }}, ИНН {{ $author['inn_for_doc'] }}, Режим НО: НПД, с
        одной стороны и<br>
        <strong>Заказчик:</strong> ИП Иванникова Алла Евгеньевна, ИНН 940300917479 с другой стороны, составили настоящий
        Акт о том, что Исполнитель оказал, а Заказчик принял услуги по написанию текстов:
    </div>
    <table class="table">
        <tbody>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th style="white-space: nowrap;">Ед.изм</th>
            <th style="white-space: nowrap;">Кол-во</th>
            <th>Цена</th>
            <th style="white-space: nowrap;">Сумма</th>
        </tr>
        @foreach($articles as $i => $article)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $article->article }}</td>
                <td style="white-space: nowrap;">тыс. ЗБП</td>
                <td>{{ $article->without_space }}</td>
                <td>{{ number_format($article->price_author, 2, ',', ' ') }}</td>
                <td style="white-space: nowrap;">{{ number_format($article->price_article, 2, ',', ' ') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <br>
    <div style="float: right; height: 16px;"><strong>ИТОГО: {{ number_format($amount['originAmount'], 2, ',', ' ') }}</strong></div>
    <br>
    <div style="float: right; height: 16px;"><strong>Без налога (НДС) —</strong></div>
    <br>
    <br>
    <br>

    <div>
        <strong>Основание:</strong>
        Договор №
        <span class="underline" style="padding: 0 5px;">{{ $author['contract_number_for_doc'] }}</span>
        от
        "<span class="underline">{{ $dateDocumentAuthor['day'] }}</span>"
        <span class="underline" style="padding: 0 5px;">{{ $dateDocumentAuthor['month'] }} {{ $dateDocumentAuthor['year'] }}</span> г.
    </div>

    <br>

    <div>Всего оказано услуг на сумму:</div>
    <div><span class="underline"
               style="padding: 0 20px 0 5px">{{ \App\Helpers\DocumentHelper::numberToWords($amount['amount']) }}</span> рублей <span
            class="underline" style="padding: 0 20px 0 5px">{{ \App\Helpers\DocumentHelper::numberToWords($amount['decimal']) }}</span>
        коп.
    </div>
    <div>в т.ч. НДС – ___00___ рублей_00__ копеек.</div>

    <br>
    <br>
    <br>
    <div>Вышеперечисленные услуги выполнены полностью и в срок. Заказчик претензий по объему, качеству и срокам оказания
        услуг претензий не имеет.
    </div>

    <div class="sign">
        <div class="sign_1">Исполнитель
            <div class="sign_line">
                _______________________________
                <div class="sign_name">{{ $author['nameAndInitials'] }}</div>
            </div>
            <div class="sign_mp">М. П.</div>
        </div>
        <div class="sign_2">Заказчик
            <div class="sign_line">
                _______________________________
                <div class="sign_name">Иванникова А. Е.</div>
                <img class="sign_img" src="{{ asset('img/ttt.png') }}" alt="">
            </div>
            <div class="sign_mp">М. П.</div>
        </div>
    </div>
</div>
</body>
</html>

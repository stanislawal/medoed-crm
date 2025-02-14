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

        .title{
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
    <div class="title">Акт</div>
    <div class="title">приема-передачи оказанных услуг № _____</div>
    <div class="title">от “___”_____________ г.</div>
    <br>
    <br>
    <div>
        <strong>Исполнитель:</strong>  ФИО автора, ИНН, Режим НО: НПД, с одной стороны и<br>
        <strong>Заказчик:</strong> ИП Иванникова Алла Евгеньевна, ИНН 940300917479 с другой стороны, составили настоящий Акт о том, что Исполнитель оказал, а Заказчик принял услуги по написанию текстов:
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
        @php $allSum = 0;  @endphp
        @foreach($articles as $i => $article)
            @php $sum = ($article->without_space / 1000) * ($article->price_author ?? 0); @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $article->article }}</td>
                <td style="white-space: nowrap;">тыс. ЗБП</td>
                <td>{{ $article->without_space }}</td>
                <td>{{ number_format($article->price_author ?? 0, 2, ',', ' ') }}</td>
                <td style="white-space: nowrap;">{{ number_format($sum, 2, ',', ' ') }}</td>
            </tr>

           @php $allSum += $sum;  @endphp
        @endforeach
        </tbody>
    </table>

    <br>
    <div style="float: right; height: 16px;"><strong>ИТОГО: {{ number_format($allSum, 2, ',', ' ') }}</strong></div><br>
    <div style="float: right; height: 16px;"><strong>Без налога (НДС)   —</strong></div>
    <br>
    <br>
    <br>

    <div>
        <strong>Основание:</strong>  Договор № ________от “___”__________2025 г.
    </div>

    <br>

    <div>Всего оказано услуг на сумму:</div>
    <div>______________________________________________________________ рублей ___ коп.</div>
    <div>в т.ч. НДС – ___00___ рублей_00__ копеек.</div>

    <br>
    <br>
    <br>
    <div>Вышеперечисленные услуги выполнены полностью и в срок. Заказчик претензий по объему, качеству и срокам оказания услуг претензий не имеет.</div>

    <div class="sign">
        <div class="sign_1">Исполнитель _______________________________<div class="sign_mp">М. П.</div></div>
        <div class="sign_2">Заказчик _______________________________<div class="sign_mp">М. П.</div></div>
    </div>
</div>
</body>
</html>

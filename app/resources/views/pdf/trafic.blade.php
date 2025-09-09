<!DOCTYPE html>
<html>
<head>
    <title>Обращение №</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<style type="text/css">
    /* @font-face {
        font-family: "Cantarell";
        font-style: normal;
        font-weight: 400;
        src: url("/fonts/Cantarell.otf");
    } */
    body{
        /* background:#f2f2f2; */
        font-family: "DejaVu Sans" !important;
    }
    .client h2{
        font-weight: normal;
        margin: 0px;
        padding: 0px;
        font-size: 26px;
    }
    .client .client-name{
        font-weight: bold;
    }
    .client .client-phone{
        font-style: normal;
    }
    hr{
        color: #333;
        background: #333;
    }
    .alert-block{
        position: absolute;
        right: 60px;
        top: 150px;
        display:inline-block;
        border: 3px solid red;
        font-size: 30px;
        padding:5px;
        line-height: 30px;
    }
</style>
<body>
    <div class="">
        <div style="font-size: 30px;margin:0px;padding:0px;">
            Обращение №{{$trafic->id}}
        </div>
    </div>

    <hr>

    <div>
        <div>
            Регистрация: {{$trafic->end_at->format('d.m.Y (H:i)')}}
        </div>

        <div>
            Автор: {{$trafic->author->cut_name}}
        </div>

        <div>
            Статус: {{$trafic->status->description}}
        </div>

        <div>
            Ответственный: {{$trafic->author->cut_name}}
        </div>

        <div>
            Время на обработку {{$trafic->interval}} мин.
            {{$trafic->begin_at->format('d.m.Y (H:i)')}} -
            {{$trafic->end_at->format('d.m.Y (H:i)')}}
        </div>

        <div>
            Обработано: {{$trafic->processing_at ? $trafic->processing_at->format('d.m.Y (H:i)') : 'Нет'}}
        </div>
    </div>

    <div class="alert-block">
        @if( date("d.m.Y H:i", strtotime("+".$trafic->interval." minutes")) > $trafic->end_at->format('d.m.Y H:i') )
            ПРОСРОЧЕНО
        @endif
    </div>

    <div class="pt-3">
        <div><b>Структура обращения</b></div>

        <div>
            {{$trafic->chanel->name}}
            {{ $trafic->chanel->myparent->id ? ('('.$trafic->chanel->myparent->name.')') : '' }}
        </div>

        <div>
            {{$trafic->salon->name}} |
            {{$trafic->structure->name}} |
            {{$trafic->appeal->name}}
        </div>

        <div>
            @if($trafic->needs)
                @foreach($trafic->needs as $itemNeed)
                    @if (!$loop->first)
                        |
                    @endif
                    <span>{{$itemNeed->name}}</span>
                @endforeach
            @else
                <i>Товары/услуги не выбраны</i>
            @endif
        </div>
    </div>

    <div class="pt-5 client">
        <div>
            <div style="font-size: 30px;margin:0px;padding:0px;">{{$trafic->lastname}} {{$trafic->firstname}}</div>
        </div>

        <div>
            <div style="font-size: 30px;margin:0px;padding:0px;">
                @if($trafic->phone)
                    {{$trafic->formated_phone}}
                @else
                    Нет номера телефона
                @endif
            </div>
        </div>

        <div>
            <div style="font-size: 30px;margin:0px;padding:0px;">
                @if($trafic->email)
                    Электронная почта: {{$trafic->email}}
                @else
                    Электронная почта: не указана
                @endif
            </div>
        </div>

        <div>
            <div style="font-size: 30px;margin:0px;padding:0px;">
                @if($trafic->zone->id)
                    Зона контакта: {{$trafic->zone->name}}
                @else
                    Зона контакта: неизвестна
                @endif
            </div>
        </div>

        <div>
            <p>
            @if($trafic->comment)
                <b>Комментарий: </b> {{$trafic->comment}}
            @else
                <b>Комментарий: </b> отсутствует
            @endif
            </p>
        </div>
    </div>

    <hr>

</body>
</html>

<table>
    <thead>
    <tr>
        <td colspan="14" size="5" align="center">Экспорт трафика</td>
    </tr>

    <tr>
        <th align="center">Номер <br>обращения</th>
        <th align="center">Дата <br> создания</th>
        {{-- <th>Дата изменения</th> --}}
        <th align="center">Автор</th>
        <th align="center">Менеджер</th>
        <th align="center">Пол</th>
        <th align="center">Зона <br>контакта</th>
        <th align="center">Канал <br> трафика</th>
        <th align="center">Салон</th>
        <th align="center">Подразделение</th>
        <th align="center">Цель <br> обращения</th>
        <th align="center">Клиент</th>
        <th align="center">Номер <br> телефона</th>
        <th align="center">Комментарий</th>
    </tr>
    </thead>
    <tbody>
    @foreach($trafics as $item)
        <tr>
            <td align="left">{{$item->id}} </td>
            <td> {{$item->created_at->format('d.m.Y (H:i)')}} </td>
            {{-- <td> {{$item->updated_at->format('d.m.Y (H:i)')}} </td> --}}
            <td> {{$item->author->cut_name}} </td>
            <td> {{$item->manager->cut_name}} </td>
            <td> {{$item->sex->name}} </td>
            <td> {{$item->zone->name}} </td>
            <td> {{$item->chanel->name}} </td>
            <td> {{$item->salon->name}} </td>
            <td> {{$item->structure->name}} </td>
            <td> {{$item->appeal->name}} </td>
            <td> {{$item->lastname.' '.$item->firstname.' '.$item->fathername}} </td>
            <td> {{$item->formated_phone}} </td>
            <td> {{$item->comment}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

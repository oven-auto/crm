<table>
    <thead>
    <tr>
        <td colspan="6" size="5" align="center">Экспорт клиентов</td>
    </tr>

    <tr>
        <th align="center">Номер <br>клиента</th>
        <th align="center">Дата <br> регистрации</th>
        <th align="center">Тип</th>
        <th align="center">ФИО</th>
        <th align="center">Пол</th>
        <th align="center">Зона <br>контакта</th>
    </tr>
    </thead>
    <tbody>
    @foreach($clients as $item)
        <tr>
            <td align="left">{{$item->id}} </td>
            <td> {{$item->created_at->format('d.m.Y')}} </td>
            <td> {{$item->type->name}} </td>
            <td> {{$item->full_name}} </td>
            <td> {{$item->sex->name}} </td>
            <td> {{$item->zone->name}} </td>
        </tr>
    @endforeach
    </tbody>
</table>

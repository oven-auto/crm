<table>
    <thead>
    <tr>
        <td colspan="6" size="5" align="center">Экспорт кредитов</td>
    </tr>

    <tr>
        <th align="center">id</th>
    </tr>
    </thead>
    <tbody>
    @foreach($services as $item)
        <tr>
            <td align="left">{{$item->id}} </td>
            
            <td align="left">{{$item->worksheet->client->full_name}}</td>

            <td align="left">{{$item->car->carable->vin}}</td>

            <td align="left">{{$item->car->carable->brand->name}} {{$item->car->carable->mark->name}} </td>

            <td align="left">{{$item->car->carable->year}} </td>
        </tr>
    @endforeach
    </tbody>
</table>

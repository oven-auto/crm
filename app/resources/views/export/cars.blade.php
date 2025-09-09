<table>
    <thead>
    <tr>
        <td colspan="14" size="5" align="center">Экспорт автомобилей (ограничение = 1000 машин)</td>
    </tr>

    <tr>
        <th align="center" width="6">П/П</th>

        <th align="center">Марка</th>
        <th align="center">Модель</th>
        <th align="center" width="10">№ заказа</th>

        <th align="center" width="8">Год<br>выпуска</th>

        <th align="center">VIN</th>
        <th align="center">Коммерческий цвет</th>

        <th align="center" width="14">Дата<br>выкупа</th>
        <th align="center">ФИО Клиента</th>
        <th align="center" width="11">Сумма всех<br>оплат</th>
        <th align="center" width="14">Автор<br>резерва</th>
        <th align="center" width="14">Менеджер<br>продажи</th>
        <th align="center" width="14">Оформитель<br>выдачи</th>
        <th align="center" width="14">Дата<br>продажи</th>
        <th align="center" width="14">Дата<br>списания</th>
        <th align="center">VIN ТИ</th>

        <th align="center">ТОРГ-12</th>

        <th align="center" width="14">Приемка<br>на склад</th>
        <th align="center" width="14">Принимающий<br>техник</th>

        <th align="center" width="14">Пл.период</th>
        <th align="center" width="14">Срок<br>оплаты</th>
        <th align="center" width="14">Дата<br>выкупа</th>
        <th align="center">Закуп</th>
        <th align="center">РРЦ</th>

        <th align="center">Дооценка</th>
        <th align="center">Сумма<br>скидок</th>
        <th align="center">Сумма<br>возмещений</th>
        <th align="center">Скидка "З/Н"</th>

        <th align="center">Опции</th>
        <th align="center">Тюнинг</th>

        <th align="center">Подарки</th>
        <th align="center">Допы</th>
    </tr>
    </thead>
    <tbody>
    @foreach($trafics as $key => $item)
        <tr>
            <td align="left">{{$key}}</td>

            <td align="left">{{$item->brand->name}}</td>
            <td align="left">{{$item->mark->name}}</td>
            <td align="left">{{$item->getOrderNumber()}} </td>

            <td align="left">{{$item->year}}</td>

            <td align="left">{{$item->vin}} </td>
            <td align="left">{{$item->color->name}}</td>

            <td align="left">{{$item->getRansomDate()}}</td>
            <td align="left">{{$item->getClientName()}}</td>
            <td align="left">{{$item->getPaymentSum()}}</td>
            <td align="left">{{$item->getReserveAuthorName()}}</td>
            <td align="left">{{$item->getSaleManager()}}</td>
            <td align="left">{{$item->getIssueManager()}}</td>
            <td align="left">{{$item->getSaleDate()}}</td>
            <td align="left">{{$item->getOffDate()}}</td>
            <td align="left">{{$item->getTradinVINString()}}</td>

            <td align="left">{{$item->getInvoiceDate()}}</td>
            
            <td align="left">{{$item->getStockDate()}}</td>
            <td align="left">{{$item->getTechnicName()}}</td>

            <td align="left">{{$item->paid_date->date ?? ''}}</td>
            <td align="left">{{$item->control_paid_date->date ?? ''}}</td>
            <td align="left">{{$item->getRansomDate()}}</td>
            <td align="left">{{$item->purchase->cost ?? ''}}</td>
            <td align="left">{{$item->getComplectationPrice()}}</td>

            <td align="left">{{$item->getOverPrice()}}</td>
            <td align="left">{{$item->getReserveSale()}}</td>

            <td align="left">{{$item->getSaleReparation()}}</td>
            <td align="left">{{$item->getExportedSaleSum()}}</td>

            <td align="left">{{$item->getOptionPrice()}}</td>
            <td align="left">{{$item->getTuningPrice()}}</td>

            <td align="left">{{$item->getGiftPrice()}}</td>
            <td align="left">{{$item->getPartPrice()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

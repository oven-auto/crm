<?php

namespace App\Classes\Telegram\Notice;

use \App\Models\ClientEventStatus;

Class WSMRedemptionCarNotice extends AbstractNotice
{
    public $message = '';



    /**
     * CHANGE
     */
    public function update($old)
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Оценка №'.$this->model->id).' (РЛ №'.$this->model->worksheet_id.').';
        $arr[] = $this->model->client_car->brand->name.' '.$this->model->client_car->mark->name.', '.$this->model->client_car->year;
        $arr[] = $user.': Меняю параметры заявки на оценку.';
        $arr[] = self::crossStr(array_shift($old));
        $arr[] = $this->model->type->name.' ('.$this->model->car_sale_sign->name.'), '.$this->model->expectation.'р.';
        $arr[] = self::italicStr('Если Вы не хотите отслеживать работу с этим клиентом, отчитайтесь по РЛ.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    /**
     * CALCULATE
     */
    public function calculate($old)
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Оценка №'.$this->model->id).' (РЛ №'.$this->model->worksheet_id.').';
        $arr[] = $this->model->client_car->brand->name.' '.$this->model->client_car->mark->name.', '.$this->model->client_car->year;
        $arr[] = $user.': '.array_shift($old);
        $arr[] = self::italicStr('Если Вы не хотите отслеживать работу с этим клиентом, отчитайтесь по РЛ.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }




    /**
     * CAR TO STOCK
     */
    public function buy()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Оценка №'.$this->model->id).' (РЛ №'.$this->model->worksheet_id.').';
        $arr[] = $this->model->client_car->brand->name.' '.$this->model->client_car->mark->name.', '.$this->model->client_car->year;
        $arr[] = $user.': Автомобиль принят на склад за '.$this->model->last_offer->price.'р.';
        $arr[] = self::italicStr('Если Вы не хотите отслеживать работу с этим клиентом, отчитайтесь по РЛ.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    /**
     * CLOSE
     */
    public function close()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Оценка №'.$this->model->id).' (РЛ №'.$this->model->worksheet_id.').';
        $arr[] = $this->model->client_car->brand->name.' '.$this->model->client_car->mark->name.', '.$this->model->client_car->year;
        $arr[] = $user.': Оценка а/м упущена.';
        $arr[] = self::italicStr('Если Вы не хотите отслеживать работу с этим клиентом, отчитайтесь по РЛ.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }
}

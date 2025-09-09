<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"mark_id", "brand_id", "complectation_id", "color_id", "year", "trade_marker_id", "provider_id"}
 * )
 */ 
class CarCreateRequest extends FormRequest
{
    public $vinRule = 'sometimes|string|nullable|unique:App\Models\Car,vin';
    public $orderRule = 'sometimes|string|nullable|unique:App\Models\CarOrder,order_number';

    public function __construct()
    {
        parent::__construct();
        
        if(request()->method('PATCH'))
            if(isset(request()->car))
            {
                $car = request()->car;
                $vin = $car->vin;
                $orderNumber = $car->order->order_number;
                if(request()->has('order_number') && $orderNumber == request()->get('order_number'))
                    $this->orderRule = '';
                if(request()->has('vin') && $vin == request()->get('vin'))
                    $this->vinRule = '';
            } 
    }



    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /** @OA\Property(format="string", description="Комментарий", property="comment", type="string") */
            'comment'           => 'sometimes|string|nullable',

            /** @OA\Property(format="string", description="Модель", property="mark_id", type="string") */
            'mark_id'           => 'required|numeric',

            /** @OA\Property(format="string", description="Бренд", property="brand_id", type="string") */
            'brand_id'          => 'required|numeric',

            /** @OA\Property(format="string", description="Комплектация", property="complectation_id", type="string") */
            'complectation_id'  => 'required|numeric',

            /** @OA\Property(format="string", description="Цвет", property="color_id", type="string") */
            'color_id'          => 'required|numeric',

            /** @OA\Property(format="string", description="Год", property="year", type="string") */
            'year'              => 'required|numeric',

            /** @OA\Property(format="string", description="ВИН", property="vin", type="string") */
            'vin'               => $this->vinRule,

            /** @OA\Property(format="string", description="Номер заказа", property="order_number", type="string") */
            'order_number'      => $this->orderRule,

            /** @OA\Property(format="string", description="Товарный признак", property="marker_id", type="string") */
            'marker_id'         => 'sometimes|numeric|nullable',

            /** @OA\Property(format="string", description="Контрмарка", property="trade_marker_id", type="string") */
            'trade_marker_id'   => 'required|numeric',

            /** @OA\Property(format="string", description="Поставщик", property="provider_id", type="string") */
            'provider_id'       => 'required|numeric',

            /** @OA\Property(format="string", description="Тип заказа", property="order_type_id", type="string") */
            'order_type_id'     => 'sometimes|numeric|nullable',

            /** @OA\Property(format="string", description="Дата заказа", property="order_date", type="string") */
            'order_date'        => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Дата сборки планируемая", property="plan_date", type="string") */
            'plan_date'         => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Дата сборки фактическая", property="build_date", type="string") */
            'build_date'        => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Дата готовность к отгрузке", property="ready_date", type="string") */
            'ready_date'        => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Дата заявка на перевозку", property="request_date", type="string") */
            'request_date'      => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Дата отгрузка на склад", property="shipment_date", type="string") */
            'shipment_date'     => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Дата приемка на склад", property="stock_date", type="string") */
            'stock_date'        => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Приемщик", property="technic_id", type="string") */
            'technic_id'        => 'sometimes|numeric|nullable',

            /** @OA\Property(format="string", description="Дата предпродажной подготовки", property="presale_date", type="string") */
            'presale_date'      => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Аудиокод", property="audio_code", type="string") */
            'audio_code'        => 'sometimes|string|nullable',

            /** @OA\Property(format="string", description="Дата приходная накладная", property="invoice_date", type="string") */
            'invoice_date'      => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Дата выкуп у поставщика", property="ransom_date", type="string") */
            'ransom_date'       => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Дата списания", property="off_date", type="string") */
            'off_date'          => 'sometimes|date_format:d.m.Y|nullable',

            /** @OA\Property(format="string", description="Владелец (на кого списали)", property="owner", type="string") */
            'owner'             => 'sometimes',

            /** @OA\Property(format="string", description="Закуп", property="purchase_cost", type="string") */
            'purchase_cost'     => 'sometimes|numeric|nullable',

            /** @OA\Property(format="string", description="Условие поставки", property="delivery_term_id", type="string") */
            'delivery_term_id'    => 'sometimes|nullable',

            /** @OA\Property(format="string", description="Не учитывать в продаже(не используется)", property="disable_sale", type="string") */
            'disable_sale'      => 'sometimes|boolean|nullable',
            
            /** @OA\Property(format="string", description="Не учитывать в плане", property="disable_off", type="string") */
            'disable_off'       => 'sometimes|boolean|nullable',

            /** @OA\Property(format="string", description="Держатель залога", property="collector_id", type="string") */
            'collector_id'      => 'sometimes|nullable',

            /** @OA\Property(format="string", description="Стоимость подарка", property="gift_price", type="string") */
            'gift_price' => 'sometimes',

            /** @OA\Property(format="string", description="Стоимость запчастей", property="part_price", type="string") */
            'part_price' => 'sometimes',

            /** @OA\Property(format="string", description="Стоимость тюнинга", property="tuning_price", type="string") */
            'tuning_price' => 'sometimes',

            /** @OA\Property(format="string", description="Начало платного периода", property="paid_date", type="string") */
            'paid_date' => 'sometimes',

            /** @OA\Property(format="string", description="Контроль оплаты", property="control_paid_date", type="string") */
            'control_paid_date' => 'sometimes',

            //Detailing cost
            /** @OA\Property(format="string", description="Себестоимость (массив [detailing_cost_id,price,coefficient])", property="detailing_costs", type="string") */
            'detailing_costs'                       => 'sometimes|array|nullable',
            'detailing_costs.*.detailing_cost_id'   => 'numeric|nullable|nullable',
            'detailing_costs.*.price'               => 'numeric|nullable|nullable',
            'detailing_costs.*.coefficient'         => 'numeric|nullable|nullable',

            /** @OA\Property(format="string", description="Номенклатура тюнинга (массив [tuningId, ..., ])", property="devices", type="string") */
            'devices'    => 'array',
            'devices.*'  => 'numeric',

            /** @OA\Property(format="string", description="Номенклатура опций (массив [optionId, ..., ])", property="options", type="string") */
            'options'           => 'sometimes|array|nullable',
            'options.*'         => 'numeric',
        ];
    }



    public function messages()
    {
        return [
            'mark_id.required'              => 'Поле модель обязательно',
            'brand_id.required'             => 'Поле бренд обязательно',
            'complectation_id.required'     => 'Поле комплектация обязательно',
            'color_id.required'             => 'Поле цвет обязательно',
            'year.required'                 => 'Поле год выпуска обязательно',
            'vin.required'                  => 'Поле VIN обязательно',
            'order_number.required'         => 'Поле номер заказа обязательно',
            'trade_marker_id.required'      => 'Поле товарный признак обязательно',
            'provider_id.required'          => 'Поле поставщик обязательно',
            'vin.unique'                    => 'ВИН не уникален',
            'order_number.unique'           => 'Номер заказа не уникален',
        ];
    }
}

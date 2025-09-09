<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ЭТА МОДЕЛЬ ЯВЛЯЕТСЯ ОТОБРАЖЕНИЕМ VIEW - car_option_prices
 * car_id | price
 * В НЕЕ НЕЛЬЗЯ СОХРАНЯТЬ ИЗ ПЫХИ
 * VIEW САМ ПЕРЕЗАПИШЕТ ВСЕ ИЗМЕНЕНИЯ
 * В ЗАВИСИМОСТИ ОТ ОПЦИИ ДОБАВЛЕНЫХ В МАШИНУ
 */
class CarOptionPrice extends Model
{
    use HasFactory;
}

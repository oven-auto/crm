<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ЭТА МОДЕЛЬ ЯВЛЯЕТСЯ ОТОБРАЖЕНИЕМ VIEW - car_full_prices
 * car_id | price
 * В НЕЕ НЕЛЬЗЯ СОХРАНЯТЬ ИЗ ПЫХИ
 * VIEW САМ ПЕРЕЗАПИШЕТ ВСЕ ИЗМЕНЕНИЯ
 * В ЗАВИСИМОСТИ ОТ ЦЕНЫ КУЗОВА, ОПЦИЙ, ВОЗДУХА И ТЮНИНГА
 */
class CarFullPrice extends Model
{
    use HasFactory;
}

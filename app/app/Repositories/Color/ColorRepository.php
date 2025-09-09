<?php

namespace App\Repositories\Color;

use \App\Http\Filters\ColorFilter;
use App\Models\Color;

Class ColorRepository
{
    public function filter($data = [])
    {
        $query = Color::with('brand');
        $filter = app()->make(ColorFilter::class, ['queryParams' => array_filter($data)]);
        $colors = $query->filter($filter)->orderBy('brand_id')->orderBy('name')->get();
        return $colors;
    }

    public function save(Color $color, $data = [])
    {
        $color->fill($data)->save();
        return $color;
    }
}

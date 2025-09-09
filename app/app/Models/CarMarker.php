<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMarker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;



    public function marker()
    {
        return $this->hasOne(\App\Models\Marker::class, 'id', 'marker_id')->withDefault();
    }



    public function getMarkerArray()
    {
        return [
            'name' => $this->marker->name,
            'description' => $this->marker->description,
            'text_color' => $this->marker->text_color,
            'body_color' => $this->marker->body_color,
        ];
    }
}

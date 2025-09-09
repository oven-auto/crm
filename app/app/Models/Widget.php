<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function body()
    {
        return $this->hasOne(\App\Models\WidgetBody::class)->withDefault();
    }

    public function banner()
    {
        return $this->hasOne(\App\Models\WidgetBanner::class)->withDefault();
    }

    public function badges()
    {
        return $this->hasMany(\App\Models\WidgetBadge::class);
    }
}

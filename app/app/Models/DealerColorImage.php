<?php

namespace App\Models;

use App\Helpers\Url\WebUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerColorImage extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function bodywork()
    {
        return $this->hasOne(\App\Models\BodyWork::class, 'id','body_work_id');
    }



    /**
     * GET FILE URL
     */
    public function getUrlAttribute()
    {
        if ($this->image)
            return WebUrl::make_link($this->image, false);
        return '';
    }
}

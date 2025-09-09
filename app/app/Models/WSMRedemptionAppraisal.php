<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMRedemptionAppraisal extends Model
{
    private const LINK = 'https://lk.cm.expert/appraisals';

    use HasFactory;

    protected $table = 'wsm_redemption_appraisals';

    protected $with = ['author'];

    protected $guarded = [];


    
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function url()
    {
        if($this->link_id)
            return self::LINK.'/'.$this->link_id;
        return '';
    }
}

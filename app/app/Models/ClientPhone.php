<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPhone extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function getPhoneMaskAttribute()
    {
        $phone = '';
        $from = $this->phone;
        if($this->phone)
            $phone = sprintf("+%s (%s) %s-%s-%s",
                mb_substr($from, 0, 1),
                mb_substr($from, 1, 3),
                mb_substr($from, 4, 3),
                mb_substr($from, 7, 2),
                mb_substr($from, 9)
            );
        return $phone;
    }

    public function getHiddenPhoneAttribute()
    {
        $from = $this->phone;
        $phone = '';
        if(strlen($this->phone) < 11)
            $from = str_pad($this->phone, 11, '*');
        elseif(strlen($this->phone) > 11)
            $from = mb_substr($this->phone,0,11);

        $phone = sprintf("+%s (%s) %s-%s-%s",
            mb_substr($from, 0, 1),
            mb_substr($from, 1, 3),
            '***',
            mb_substr($from, 7, 2),
            mb_substr($from, 9)
        );

        return $phone;
    }

    public function client()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'client_id')->withDefault();
    }
}

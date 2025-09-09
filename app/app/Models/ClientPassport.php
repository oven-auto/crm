<?php

namespace App\Models;

use App\Models\Interfaces\EventInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClientPassport extends Pivot implements EventInterface
{
    use HasFactory;

    protected $casts = [
        'birthday_at' => 'datetime',
        'driver_license_issue_at' => 'datetime',
        'passport_issue_at' => 'datetime',
    ];

    protected $guarded = [];

    public $timestamps = false;

    public $table = 'client_passports';


    public static function getColumnsName()
    {
        $clientPassport = new ClientPassport();
        return $clientPassport->getConnection()->getSchemaBuilder()->getColumnListing($clientPassport->getTable());
    }



    public function getBirthdayAttribute()
    {
        if($this->birthday_at)
            return $this->birthday_at->format('d.m.Y');
        return '';
    }



    public function client()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'client_id');
    }
}

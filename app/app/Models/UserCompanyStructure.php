<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompanyStructure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id')->withTrashed();
    }

    public function company()
    {
        return $this->hasOne(\App\Models\Company::class, 'id', 'company_id')->withDefault();
    }

    public function structure()
    {
        return $this->hasOne(\App\Models\CompanyStructure::class, 'id', 'company_structure_id')->withDefault();
    }
}

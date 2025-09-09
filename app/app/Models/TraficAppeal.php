<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraficAppeal extends Model
{
    use HasFactory;


    public function appeal()
    {
        return $this->hasOne(
            \App\Models\Appeal::class,
            'id',
            'appeal_id'
        )->withDefault();
    }

    public function structure()
    {
        return $this->hasOneThrough(
            \App\Models\Structure::class,
            \App\Models\CompanyStructure::class,
            'id',
            'id',
            'company_structure_id',
            'structure_id'
        );
    }

    public function company()
    {
        return $this->hasOneThrough(
            \App\Models\Company::class,
            \App\Models\CompanyStructure::class,
            'id',
            'id',
            'company_structure_id',
            'company_id'
        );
    }
}

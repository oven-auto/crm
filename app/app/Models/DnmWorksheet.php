<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnmWorksheet extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function appeals()
    {
        return $this->hasMany(DnmWorksheetAppeal::class, 'dnm_worksheet_id', 'id');
    }



    public function events()
    {
        //return $this->hasMany(DnmWorksheetEvent::class, 'dnm_worksheet_id', 'i')
    }
}

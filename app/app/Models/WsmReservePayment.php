<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WsmReservePayment extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = [];

    public $casts = [
        'date_at' => 'date'
    ];



    public function writeComment(array $data)
    {
        return WsmReserveComment::create($data);
    }



    /**
     * RELATIONS
     */

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function reserve()
    {
        return $this->hasOne(\App\Models\WsmReserveNewCar::class, 'id', 'reserve_id')->withTrashed();
    }



    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'id', 'payment_id');
    }
}

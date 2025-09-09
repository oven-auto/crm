<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Services\Comment\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraficClient extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'client_type_id',
        'trafic_sex_id',
        'firstname',
        'lastname',
        'fathername',
        'phone',
        'trafic_id',
        'inn',
        'company_name',
        'empty_phone',
        'email',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function($item){
            Comment::add($item, 'create');
        });

        static::updated(function($item){
            if($item->isDirty())
                Comment::add($item, 'update');
        });
    }



    public function person()
    {
        return $this->hasOne(\App\Models\ClientType::class, 'id', 'client_type_id')->withDefault();
    }



    public function sex()
    {
        return $this->hasOne(\App\Models\TraficSex::class, 'id', 'trafic_sex_id')->withDefault();
    }



    public function writeComment(array $data)
    {
        return TraficComment::create($data);
    }



    public function getData()
    {
        $arr = [];

        foreach($this->getAttributes() as $key => $item)
        {
            if($item != '' && in_array($key, $this->fillable))
                if($key == 'client_type_id')
                    $arr[$key] = $this->person->name;
                elseif($key == 'trafic_sex_id')
                    $arr[$key] = $this->sex->name;
                else
                    $arr[$key] = $item;
        }

        return $arr;
    }



    public function getToString()
    {
        return implode(', ', $this->getData());
    }
}

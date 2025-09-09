<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Models\Interfaces\PersonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Createable;
use App\Models\Interfaces\EventInterface;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model implements PersonInterface, CommentInterface, EventInterface
{
    use HasFactory, Createable, Filterable, SoftDeletes;

    protected $guarded = [];

    public function loyalty()
    {
        return 'Неизвестно';
    }



    public function abbreviated_name()
    {
        return $this->full_name;
    }



    public function writeComment(array $data)
    {
        ClientComment::create($data);
    }



    public function getFullNameAttribute()
    {
        if($this->isPerson())
        {
            $mas[] = $this->lastname;
            $mas[] = $this->firstname;
            $mas[] = $this->fathername;
            $result = trim(implode(' ', $mas));
            return $result;
        }           
        
        return $this->company_name;
    }



    public function getFullNameOrTypeAttribute()
    {
        if ($this->full_name)
            return $this->full_name;
        return $this->type->name;
    }



    public static function getColumnsName()
    {
        $client = new Client();
        return $client->getConnection()->getSchemaBuilder()->getColumnListing($client->getTable());
    }



    public function critical()
    {
        return '';
    }



    public static function getOwner()
    {
        return Client::select('clients.*')
            ->with(['inn'])
            ->leftJoin('client_inns', 'client_inns.client_id', 'clients.id')
            ->where('number', '1121000102')
            ->first();
    }



    public function initials()
    {
        if (isset($this->company_name) && !empty($this->company_name))
            return  mb_substr($this->company_name, 0, 1);

        return mb_substr($this->lastname, 0, 1) . mb_substr($this->firstname, 0, 1);
    }



    public static function findByPhone($phone_number)
    {
        $result = self::select('clients.*')
            ->join('client_phones', 'client_phones.client_id', '=', 'clients.id')
            ->where('client_phones.phone', $phone_number)
            ->first();

        return $result ?? new Client();
    }



    public function dnm()
    {
        return $this->hasOne(\App\Models\DnmClient::class, 'client_id', 'id');
    }



    public function phones()
    {
        return $this->hasMany(\App\Models\ClientPhone::class, 'client_id', 'id');
    }



    public function emails()
    {
        return $this->hasMany(\App\Models\ClientEmail::class, 'client_id', 'id');
    }



    public function type()
    {
        return $this->hasOne(\App\Models\ClientType::class, 'id', 'client_type_id')->withDefault();
    }



    public function sex()
    {
        return $this->hasOne(\App\Models\TraficSex::class, 'id', 'trafic_sex_id')->withDefault();
    }



    public function zone()
    {
        return $this->hasOne(\App\Models\TraficZone::class, 'id', 'trafic_zone_id')->withDefault();
    }



    public function passport()
    {
        return $this->hasOne(\App\Models\ClientPassport::class, 'client_id', 'id')->withDefault();
    }



    public function cars()
    {
        return $this->hasMany(\App\Models\ClientCar::class, 'client_id', 'id')->where('actual', 1);
    }



    public function latest_worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'client_id', 'id')->orderBy('id', 'DESC')->withDefault();
    }



    public function worksheets()
    {
        return $this->hasMany(\App\Models\Worksheet::class, 'client_id', 'id');
    }



    public function open_worksheets()
    {
        return $this->hasMany(\App\Models\Worksheet::class, 'client_id', 'id')->where('status_id', 'work');
    }



    public function inn()
    {
        return $this->hasOne(\App\Models\ClientInn::class, 'client_id', 'id')->withDefault();
    }



    public function events()
    {
        return $this->hasMany(\App\Models\ClientEvent::class, 'client_id', 'id');
    }



    public function unionsChildren()
    {
        return $this->belongsToMany(Client::class, 'client_unions', 'parent', 'client_id')->with(['phones', 'inn']);
    }



    public function unionsParent()
    {
        return $this->belongsToMany(Client::class, 'client_unions', 'client_id', 'parent')->with(['phones', 'inn']);
    }



    public function isCompany()
    {
        if ($this->client_type_id != 1)
            return true;
        return false;
    }



    public function isPerson()
    {
        if ($this->client_type_id == 1)
            return true;
        return false;
    }



    public function isOurCompany()
    {
        return $this->client_type_id == 3 ? 1 : 0;
    }



    public function getDnmTypeClient()
    {
        return match ($this->client_type_id) {
            1 => 0,
            2 => 1,
        };
    }



    public function scopeLinksCount($query)
    {
        return $query->withCount('links');
    }



    public function scopeFilesCount($query)
    {
        return $query->withCount('files');
    }



    public function files()
    {
        return $this->hasMany(\App\Models\ClientFile::class, 'client_id', 'id');
    }



    public function links()
    {
        return $this->hasMany(\App\Models\ClientLink::class, 'client_id', 'id');
    }



    public function checkContractFields()
    {

        if ($this->isPerson())
            if (
                $this->firstname && 
                $this->lastname && 
                $this->fathername && 
                $this->trafic_zone_id && 
                $this->trafic_sex_id &&
                $this->passport->birthday_at && 
                $this->passport->address
            )
                return 1;

        if ($this->isCompany())
            if ($this->company_name && $this->trafic_zone_id)
                return 1;

        if ($this->isOurCompany())
            return 1;

        return 0;
    }



    public function getUnionClients()
    {
        $collect = collect();
        $collect->merge($this->client->unionsChildren);
        $collect->merge($this->client->unionsParent);
    }



    public function hasUnions() : bool
    {
        return $this->unions_children_count || $this->unions_parent_count;
    }
}

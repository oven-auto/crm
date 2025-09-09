<?php

namespace App\Models;

use App\Models\Interfaces\PersonInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Interfaces\GiveDataForCommentInterface;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements PersonInterface, GiveDataForCommentInterface
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'lastname',
        'phone',
        'role_id',
    ];

    public function forComment()
    {
        return $this->cut_name;
    }

    public function abbreviated_name()
    {
        return $this->cut_name;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function colleagues()//structures
    {
        return User::select('users.*')
            ->leftJoin('user_company_structures', 'user_company_structures.user_id', 'users.id')
            ->whereIn('user_company_structures.company_structure_id', $this->structures->pluck('company_structure_id'))
            ->groupBy('users.id')
            ->get();
    }

    public function scopeByStructure($query, $structure_id)
    {
        return $query
            ->select('users.id','users.name','users.lastname')
            ->leftJoin('user_company_structures','user_company_structures.user_id','=','users.id')
            ->where('user_company_structures.company_structure_id', '=', $structure_id);
    }

    public function getCutNameAttribute()
    {
        if($this->name && $this->lastname)
            return "$this->lastname ".mb_substr($this->name,0,1).'.';
        return '';
    }

    public function companies()
    {
        return $this->belongsToMany(\App\Models\Company::class, 'user_company_structures', 'user_id', 'company_id');
    }

    public function structures()
    {
        return $this->hasmany(\App\Models\UserCompanyStructure::class,'user_id','id');
    }

    public function mystructures()
    {
        return $this->belongsToMany(\App\Models\CompanyStructure::class, 'user_company_structures', 'user_id', 'company_structure_id');
        //return $this->hasmany(\App\Models\UserCompanyStructure::class,'user_id','id');
        //return $this->belongsToMany(\App\Models\Structure::class, 'user_company_structures', 'user_id', 'company_structure_id', 'id')->dd();
    }

    public function role()
    {
        return $this->hasOne(\App\Models\Role::class, 'id', 'role_id')->withDefault();
    }

    public function permissions()
    {
       return $this->belongsToMany(\App\Models\Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    //настройка вывода столбиков, с учетом количества назначеного трафика за день, за месяц на менеджере
    public function scopeCounterSelect($query)
    {
        $query->select(
            'users.id','users.name','users.lastname',
            DB::raw('sum(case when date(trafics.created_at) = curdate() then 1 else 0 end) as d_count'),
            DB::raw('sum(
                    case when
                        year(trafics.created_at) = year(now()) and
                        month(trafics.created_at) = month(now())
                    then 1
                    else 0
                    end
                ) as m_count
            ')
        );
    }

    public function appeals()
    {
        return $this->belongsToMany(\App\Models\Appeal::class, 'appeal_users', 'user_id', 'appeal_id','id');
    }



    public function trafic_appeals()
    {
        return $this->belongsToMany(\App\Models\Appeal::class, 'trafic_user_appeals', 'user_id', 'appeal_id','id');
    }



    public function forAuthor()
    {
        return [
            'id' => $this->id,
            'name' => $this->cut_name,
        ];
    }
}

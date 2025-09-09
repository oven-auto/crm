<?php

namespace App\Models\Audit;

use App\Repositories\Audit\Interfaces\AuditSortInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AuditQuestion extends Model implements AuditSortInterface
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['text', 'audit_id', 'author_id', 'sort', 'name', 'weight'];

    protected $with = ['calcweight'];



    public function calcweight()
    {
        return $this->hasOne(\App\Models\Audit\AuditWeights::class, 'audit_id', 'audit_id');
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function answers()
    {
        return $this->hasOne(\App\Models\Audit\AuditAnswer::class, 'question_id', 'id');
    }



    public function audit()
    {
        return $this->hasOne(\App\Models\Audit\Audit::class, 'id', 'audit_id');
    }



    public function subquestions()
    {
        return $this->hasMany(\App\Models\Audit\AuditSubQuestion::class, 'question_id', 'id');
    }



    public function getWeight()
    {
        return $this->weight ?? ($this->calcweight->weight ?? 0);
    }



    public static function getTotal(int|AuditQuestion|null $question)
    {
        if(!$question)
            return 0;

        if($question instanceof AuditQuestion)
            $question = $question->audit_id;

        $total = DB::table('audit_questions')
            ->select([
                DB::raw('CAST(SUM(IFNULL(audit_questions.weight, audit_weights.weight)) AS UNSIGNED) as total')
            ])
            ->leftJoin('audit_weights', 'audit_weights.audit_id', 'audit_questions.audit_id')
            ->where('audit_questions.audit_id', $question)
            ->groupBy('audit_questions.audit_id')
            ->first()
            ->total;

        return $total;
    }
}

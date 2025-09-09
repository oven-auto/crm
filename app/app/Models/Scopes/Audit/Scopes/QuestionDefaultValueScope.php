<?php

namespace App\Models\Scopes\Audit\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class QuestionDefaultValueScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->addSelect([
            DB::raw('audit_questions.*'),
            DB::raw('sum(audit_questions.weigth) OVER (PARTITION BY audit_questions.audit_id) as physic_weigth'),
            DB::raw('count(audit_questions.id) OVER (PARTITION BY audit_questions.audit_id) as count_question'),
            DB::raw('SUM(IF(audit_questions.weigth IS NOT NULL, 1, 0)) OVER (PARTITION BY audit_questions.audit_id) as count_out_weigth'),
        ]);
    }
}

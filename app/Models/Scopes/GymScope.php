<?php

namespace App\Models\Scopes;

use App\Support\GymContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class GymScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $gymId = GymContext::id();
        
        if ($gymId) {
            $builder->where($model->getTable() . '.gym_id', $gymId);
        }
    }
}

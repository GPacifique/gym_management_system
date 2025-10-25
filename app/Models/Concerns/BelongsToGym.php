<?php

namespace App\Models\Concerns;

use App\Models\Gym;
use App\Models\Scopes\GymScope;
use App\Support\GymContext;

trait BelongsToGym
{
    public static function bootBelongsToGym(): void
    {
        static::addGlobalScope(new GymScope());
        
        static::creating(function ($model) {
            if (empty($model->gym_id)) {
                $model->gym_id = GymContext::id();
            }
        });
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
    
    /**
     * Query without gym scope for admin operations.
     */
    public function scopeWithoutGymScope($query)
    {
        return $query->withoutGlobalScope(GymScope::class);
    }
}

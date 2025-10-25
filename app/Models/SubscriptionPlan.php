<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'name',
        'price',
        'duration_days',
        'status',
        'description',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}

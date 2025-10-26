<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'name',
        'code',
        'address',
        'phone',
        'email',
        'status', // active|inactive
    ];

    protected $casts = [
        'gym_id' => 'integer',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}

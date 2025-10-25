<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToGym;

class Trainer extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = ['gym_id', 'name', 'specialty', 'email'];
}

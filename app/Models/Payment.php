<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToGym;

class Payment extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = ['gym_id', 'member_id', 'subscription_id', 'amount', 'currency', 'method', 'status', 'payment_date'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}

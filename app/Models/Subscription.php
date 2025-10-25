<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToGym;

class Subscription extends Model
{
    use HasFactory, BelongsToGym;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'gym_id',
        'member_id',
        'subscription_plan_id',
        'plan_name',
        'price',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Relationships
     */

    // Each subscription belongs to a member
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    /**
     * Accessors
     */

    // Check if subscription is active
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && now()->between($this->start_date, $this->end_date);
    }

    // Format price nicely
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    // Calculate remaining days
    public function getRemainingDaysAttribute()
    {
        $end = \Carbon\Carbon::parse($this->end_date);
        $now = now();
        return $now->diffInDays($end, false); // negative if expired
    }

    /**
     * Scopes
     */

    // Quickly filter active subscriptions
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->whereDate('start_date', '<=', now())
                     ->whereDate('end_date', '>=', now());
    }

    // Filter subscriptions by member
    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }
}

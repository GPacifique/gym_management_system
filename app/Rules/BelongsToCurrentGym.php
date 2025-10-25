<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use App\Support\GymContext;

class BelongsToCurrentGym implements ValidationRule
{
    protected string $table;
    protected string $column;

    public function __construct(string $table, string $column = 'id')
    {
        $this->table = $table;
        $this->column = $column;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $gymId = GymContext::id();

        if (!$gymId) {
            $fail("No gym context available for validation.");
            return;
        }

        // Check if the record exists in the table and belongs to the current gym
        $exists = DB::table($this->table)
            ->where($this->column, $value)
            ->where('gym_id', $gymId)
            ->exists();

        if (!$exists) {
            $fail("The selected {$attribute} is invalid for your current gym.");
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchBackfillSeeder extends Seeder
{
    public function run(): void
    {
        // Create a default 'Main Branch' for gyms that don't have one
        $gymIds = DB::table('gyms')->pluck('id');
        foreach ($gymIds as $gymId) {
            $exists = DB::table('branches')->where('gym_id', $gymId)->exists();
            if (!$exists) {
                DB::table('branches')->insert([
                    'gym_id' => $gymId,
                    'name' => 'Main Branch',
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

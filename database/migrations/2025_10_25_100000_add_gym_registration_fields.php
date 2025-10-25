<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            // Only add columns that don't already exist
            if (!Schema::hasColumn('gyms', 'owner_user_id')) {
                $table->foreignId('owner_user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('gyms', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('timezone');
            }
            if (!Schema::hasColumn('gyms', 'subscription_tier')) {
                $table->string('subscription_tier')->default('trial')->after('is_verified');
            }
            if (!Schema::hasColumn('gyms', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('subscription_tier');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            if (Schema::hasColumn('gyms', 'owner_user_id')) {
                $table->dropConstrainedForeignId('owner_user_id');
            }
            $table->dropColumn([
                'is_verified',
                'subscription_tier',
                'trial_ends_at',
            ]);
        });
    }
};

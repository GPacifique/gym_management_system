<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'subscription_plan_id')) {
                $table->foreignId('subscription_plan_id')->nullable()->after('member_id')->constrained('subscription_plans')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'subscription_plan_id')) {
                $table->dropConstrainedForeignId('subscription_plan_id');
            }
        });
    }
};

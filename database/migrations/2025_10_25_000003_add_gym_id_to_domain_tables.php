<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['members','trainers','classes','attendances','payments','subscriptions','workout_plans'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                if (!Schema::hasColumn($tbl, 'gym_id')) {
                    $table->foreignId('gym_id')->nullable()->after('id')->constrained('gyms')->nullOnDelete();
                }
            });
        }

    }

    public function down(): void
    {
        foreach (['members','trainers','classes','attendances','payments','subscriptions','workout_plans'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('gym_id');
            });
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subscription_plan_id');
        });
    }
};

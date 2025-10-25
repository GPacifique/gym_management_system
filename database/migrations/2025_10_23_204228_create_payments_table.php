<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
        $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
        $table->decimal('amount', 10, 2);
        $table->enum('method', ['cash', 'card', 'bank_transfer', 'mobile_payment', 'other'])->default('cash');
        $table->date('payment_date')->default(now());
        $table->string('transaction_id')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

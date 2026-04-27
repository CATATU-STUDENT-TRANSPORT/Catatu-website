<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->enum('method', ['mpesa', 'wallet', 'card'])->default('mpesa');
            $table->decimal('amount', 8, 2);
            $table->string('phone', 20)->nullable();
            $table->string('mpesa_checkout_request_id')->nullable()->index();
            $table->string('mpesa_receipt')->nullable()->unique();
            $table->enum('status', ['initiated', 'pending', 'success', 'failed', 'reversed'])
                ->default('initiated')->index();
            $table->json('callback_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

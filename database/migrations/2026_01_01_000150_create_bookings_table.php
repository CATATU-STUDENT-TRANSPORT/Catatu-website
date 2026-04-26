<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('route_id')->constrained();
            $table->foreignId('trip_id')->nullable()->constrained();
            $table->json('seats')->nullable();
            $table->decimal('amount', 8, 2);
            $table->enum('status', [
                'pending_payment',
                'paid',
                'boarded',
                'completed',
                'cancelled',
                'refunded',
                'waitlisted',
            ])->default('pending_payment')->index();
            $table->string('boarding_pin', 6)->nullable();
            $table->string('qr_token', 64)->nullable()->unique();
            $table->dateTime('hold_expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

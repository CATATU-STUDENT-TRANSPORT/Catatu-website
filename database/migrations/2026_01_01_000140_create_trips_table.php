<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained();
            $table->foreignId('vehicle_id')->nullable()->constrained();
            $table->foreignId('driver_id')->nullable()->constrained();
            $table->dateTime('departure_time');
            $table->dateTime('arrived_at')->nullable();
            $table->enum('status', [
                'scheduled',
                'threshold_pending',
                'boarding',
                'departed',
                'arrived',
                'cancelled',
            ])->default('threshold_pending')->index();
            $table->unsignedSmallInteger('seats_total');
            $table->unsignedSmallInteger('seats_booked')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};

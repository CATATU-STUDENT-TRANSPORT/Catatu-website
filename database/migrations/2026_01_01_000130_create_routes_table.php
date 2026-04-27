<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('start_zone_id')->constrained('zones');
            $table->foreignId('end_zone_id')->constrained('zones');
            $table->string('start_location');
            $table->string('end_location');
            $table->decimal('price', 8, 2);
            $table->unsignedSmallInteger('threshold')->default(10)
                ->comment('Min seats before trip is activated');
            $table->unsignedSmallInteger('estimated_minutes')->default(45);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->float('ph')->nullable();
            $table->float('nutrisi')->nullable();
            $table->float('kekeruhan')->nullable();
            $table->float('kelembaban')->nullable();
            $table->float('pwm_pompa')->nullable();
            $table->boolean('sensor_online')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};

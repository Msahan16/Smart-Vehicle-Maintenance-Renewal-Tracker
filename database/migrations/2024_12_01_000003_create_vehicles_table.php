<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('vehicle_number')->unique();
            $table->string('brand');
            $table->string('model');
            $table->enum('fuel_type', ['Petrol', 'Diesel', 'Electric', 'Hybrid', 'CNG', 'LPG']);
            $table->string('engine_capacity')->nullable();
            $table->year('manufactured_year');
            $table->string('color')->nullable();
            $table->string('photo')->nullable();
            $table->date('license_expiry')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->date('emission_test_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

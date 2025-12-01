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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('vehicle_license_front')->nullable()->after('photo');
            $table->string('vehicle_license_back')->nullable()->after('vehicle_license_front');
            $table->string('insurance_doc_front')->nullable()->after('vehicle_license_back');
            $table->string('insurance_doc_back')->nullable()->after('insurance_doc_front');
            $table->string('emission_test_doc_front')->nullable()->after('insurance_doc_back');
            $table->string('emission_test_doc_back')->nullable()->after('emission_test_doc_front');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_license_front',
                'vehicle_license_back',
                'insurance_doc_front',
                'insurance_doc_back',
                'emission_test_doc_front',
                'emission_test_doc_back'
            ]);
        });
    }
};

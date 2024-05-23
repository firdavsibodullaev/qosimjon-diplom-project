<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calibrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_device_id')->constrained('factory_device');
            $table->foreignId('applicant_factory_id')->constrained('factories');
            $table->foreignId('applicant_id')->constrained('users');
            $table->foreignId('checker_factory_id')->constrained('factories');
            $table->foreignId('checker_id')->nullable()->constrained('users');
            $table->timestamp('checked_at')->nullable();
            $table->string('status');
            $table->string('result')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calibrations');
    }
};

<?php

use App\Enums\FactoryDevice\Position;
use App\Enums\FactoryDevice\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factory_device', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_id')->constrained();
            $table->foreignId('factory_floor_id')->constrained();
            $table->foreignId('device_id')->constrained();
            $table->integer('number');
            $table->string('full_number');
            $table->enum('status', Status::values());
            $table->enum('position', Position::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factory_device');
    }
};

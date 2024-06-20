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
        Schema::table('factory_device', function (Blueprint $table) {
            $table->after('position', function (Blueprint $table) {
                $table->dateTime('last_checked_at')->nullable();
                $table->tinyInteger('check_every_time')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factory_device', function (Blueprint $table) {
            $table->dropColumn('last_checked_at');
            $table->dropColumn('check_every_time');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `activities` MODIFY `unit` ENUM('minutes','times','hours') NOT NULL DEFAULT 'times'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `activities` MODIFY `unit` ENUM('minutes','times') NOT NULL DEFAULT 'minutes'");
    }
};

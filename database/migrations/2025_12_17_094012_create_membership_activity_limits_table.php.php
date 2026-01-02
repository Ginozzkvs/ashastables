<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_activity_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->integer('max_per_year');
            $table->integer('daily_minutes')->nullable(); // null = no daily limit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_activity_limits');
    }
};

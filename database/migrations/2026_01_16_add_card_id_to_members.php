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
        Schema::table('members', function (Blueprint $table) {
            // Drop qr_code if it exists (not needed)
            if (Schema::hasColumn('members', 'qr_code')) {
                $table->dropColumn('qr_code');
            }
        });

        Schema::table('members', function (Blueprint $table) {
            // Add card_id as varchar for format like "AS0001"
            if (!Schema::hasColumn('members', 'card_id')) {
                $table->string('card_id')->unique()->nullable()->after('card_uid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'card_id')) {
                $table->dropColumn('card_id');
            }
        });

        Schema::table('members', function (Blueprint $table) {
            $table->string('qr_code')->unique()->nullable()->after('phone');
        });
    }
};

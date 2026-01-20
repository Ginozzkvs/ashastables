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
            // Drop columns that shouldn't exist
            if (Schema::hasColumn('members', 'qr_code')) {
                $table->dropColumn('qr_code');
            }
            if (Schema::hasColumn('members', 'card_id')) {
                $table->dropColumn('card_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('qr_code')->unique()->nullable()->after('phone');
            $table->string('card_id')->nullable()->after('card_uid');
        });
    }
};

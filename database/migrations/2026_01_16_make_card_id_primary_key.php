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
        // First, add card_id column with generated values
        Schema::table('members', function (Blueprint $table) {
            $table->string('card_id')->unique()->nullable()->after('id');
        });

        // Generate card_id for existing records
        $members = \DB::table('members')->get();
        foreach ($members as $member) {
            \DB::table('members')
                ->where('id', $member->id)
                ->update(['card_id' => 'AS' . str_pad($member->id, 4, '0', STR_PAD_LEFT)]);
        }

        // Make card_id NOT NULL
        Schema::table('members', function (Blueprint $table) {
            $table->string('card_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('card_id');
        });
    }
};


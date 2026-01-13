<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('members', function (Blueprint $table) {
        $table->string('card_uid')->unique()->nullable()->after('qr_code');
    });
}

public function down()
{
    Schema::table('members', function (Blueprint $table) {
        $table->dropColumn('card_uid');
    });
}

};

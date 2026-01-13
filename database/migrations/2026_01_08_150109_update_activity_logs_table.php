<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateActivityLogsTable extends Migration
{
    public function up(): void
{
    Schema::table('activity_logs', function (Blueprint $table) {

        if (Schema::hasColumn('activity_logs', 'staff_id')) {
            $table->dropColumn('staff_id');
        }

        if (!Schema::hasColumn('activity_logs', 'user_id')) {
            $table->unsignedBigInteger('user_id')->after('id');
        }

        if (!Schema::hasColumn('activity_logs', 'user_role')) {
            $table->string('user_role')->after('user_id');
        }

        if (!Schema::hasColumn('activity_logs', 'member_id')) {
            $table->unsignedBigInteger('member_id')->after('user_role');
        }

        if (!Schema::hasColumn('activity_logs', 'card_uid')) {
            $table->string('card_uid')->after('member_id');
        }

        if (!Schema::hasColumn('activity_logs', 'activity_id')) {
            $table->unsignedBigInteger('activity_id')->after('card_uid');
        }

        if (!Schema::hasColumn('activity_logs', 'success')) {
            $table->boolean('success')->default(true);
        }

        if (!Schema::hasColumn('activity_logs', 'message')) {
            $table->string('message')->nullable();
        }
    });


    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropForeign(['activity_id']);

            $table->dropColumn([
                'user_id',
                'user_role',
                'member_id',
                'card_uid',
                'activity_id',
                'success',
                'message'
            ]);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Update all tables that reference members.id to use card_id instead
        DB::statement('ALTER TABLE member_activity_balances MODIFY member_id VARCHAR(255)');
        DB::statement('ALTER TABLE activity_logs MODIFY member_id VARCHAR(255)');
        DB::statement('ALTER TABLE activity_usages MODIFY member_id VARCHAR(255)');

        // Drop the old auto-increment id column since card_id already exists
        DB::statement('ALTER TABLE members DROP PRIMARY KEY, DROP COLUMN id, ADD PRIMARY KEY (card_id)');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // Restore id column
        DB::statement('ALTER TABLE members ADD COLUMN id BIGINT UNSIGNED AUTO_INCREMENT UNIQUE AFTER card_id');
        DB::statement('ALTER TABLE member_activity_balances MODIFY member_id BIGINT UNSIGNED');
        DB::statement('ALTER TABLE activity_logs MODIFY member_id BIGINT UNSIGNED');
    }
};

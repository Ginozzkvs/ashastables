<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update activity limits for Premium Annual membership (id: 1)
        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->where('activity_id', 1)  // Horse riding
            ->update([
                'max_per_year' => 12,
                'max_per_day' => 1,
            ]);

        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->where('activity_id', 2)  // Entry ticket
            ->update([
                'max_per_year' => 365,
                'max_per_day' => 1,
            ]);

        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->where('activity_id', 3)  // Free drink
            ->update([
                'max_per_year' => 365,
                'max_per_day' => 1,
            ]);

        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->where('activity_id', 4)  // Discount 5% (Room)
            ->update([
                'max_per_year' => 365,
                'max_per_day' => 1,
            ]);
    }

    public function down(): void
    {
        // Revert to previous values
        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->where('activity_id', 1)
            ->update(['max_per_year' => 24, 'max_per_day' => 2]);

        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->where('activity_id', 2)
            ->update(['max_per_year' => 52, 'max_per_day' => 2]);

        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->where('activity_id', 3)
            ->update(['max_per_year' => 24, 'max_per_day' => 1]);

        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->where('activity_id', 4)
            ->update(['max_per_year' => 365, 'max_per_day' => 5]);
    }
};

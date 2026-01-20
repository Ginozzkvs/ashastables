<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add activity limits to Premium Annual membership (id: 1)
        // Horse riding: 24 times per year
        // Entry ticket: 52 times per year (weekly)
        // Free drink: 24 times per year
        // Discount 5% (Room): unlimited (365 times per year)
        
        DB::table('membership_activity_limits')->insert([
            [
                'membership_id' => 1,
                'activity_id' => 1,  // Horse riding
                'max_per_year' => 24,
                'max_per_day' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_id' => 1,
                'activity_id' => 2,  // Entry ticket
                'max_per_year' => 52,
                'max_per_day' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_id' => 1,
                'activity_id' => 3,  // Free drink
                'max_per_year' => 24,
                'max_per_day' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'membership_id' => 1,
                'activity_id' => 4,  // Discount 5% (Room)
                'max_per_year' => 365,
                'max_per_day' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('membership_activity_limits')
            ->where('membership_id', 1)
            ->delete();
    }
};

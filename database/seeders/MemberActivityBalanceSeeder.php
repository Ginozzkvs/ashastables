<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberActivityBalanceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('member_activity_balances')->insert([
            'member_id' => 1,
            'activity_id' => 1,
            'remaining_count' => 20,
            'daily_limit' => 20,
            'used_today' => 0,
            'last_used_date' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            ['id' => 1, 'name' => "active"],
            ['id' => 2, 'name' => "inactive"],
            ['id' => 3, 'name' => "migrated"],
            ['id' => 4, 'name' => "suspended"],
            ['id' => 5, 'name' => "deceased"],
        ];

        DB::table('statuses')->insert($status);

        $update = [
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('statuses')->update($update);
    }
}

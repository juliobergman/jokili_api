<?php

namespace Database\Seeders;

use DateTime;
use App\Models\Election;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Election::factory(1)
        ->state([
            'name' => 'Elecciones Jokili Verein 2022',
            'status' => 1,
            'start' => '2022-02-12 11:59:59',
            'end' => '2022-02-13 18:00:00',
        ])
        ->create();
    }
}

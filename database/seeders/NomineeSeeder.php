<?php

namespace Database\Seeders;

use App\Models\Nominee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class NomineeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Nominee::factory(3)
        ->state(new Sequence(
            fn ($sequence) => ['user_id' => User::all()->random()],
        ))
        ->state(new Sequence(
            ['position_id' => 1],
            ['position_id' => 2],
            ['position_id' => 3],
            ['position_id' => 4],
            ['position_id' => 5],
            ['position_id' => 6],
            ['position_id' => 7],
            ['position_id' => 8]
        ))
        // ->state(['position_id' => 1])
        ->create();
    }
}

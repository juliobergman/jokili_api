<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nominee;
use App\Models\Election;
use App\Models\Position;
use App\Models\ElectionResult;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ElectionResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ElectionResult::factory(200)
        ->state(new Sequence(
            fn () => ['user_id' => User::all()->random()],
        ))
        ->state(new Sequence(
            fn () => ['position_id' => Position::all()->random()],
        ))
        ->state(new Sequence(
            fn () => ['nominee_id' => Nominee::all()->random()->user_id],
        ))
        ->state(new Sequence(
            fn () => ['election_id' => Election::all()->random()],
        ))
        ->create();
    }
}

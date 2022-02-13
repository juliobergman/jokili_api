<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = [
            [
                'id' => 1,
                'election_id' => 1,
                'name' => 'Oberzunftmeister',
                'info' => 'Presidente',
            ],
            [
                'id' => 2,
                'election_id' => 1,
                'name' => 'Zeremonienmeister',
                'info' => 'Maestro de Ceremonias',
            ],
            [
                'id' => 3,
                'election_id' => 1,
                'name' => 'Jokilimeister',
                'info' => 'Maestro Jokili',
            ],
            [
                'id' => 4,
                'election_id' => 1,
                'name' => 'Zunftzackelmeister',
                'info' => 'Tesorero',
            ],
            [
                'id' => 5,
                'election_id' => 1,
                'name' => 'Programm-Meister',
                'info' => 'Maestro de Programas',
            ],
            [
                'id' => 6,
                'election_id' => 1,
                'name' => 'Torbuchmeister',
                'info' => 'Secretario',
            ],
            [
                'id' => 7,
                'election_id' => 1,
                'name' => 'Stroossfasnetmeister',
                'info' => 'Maestro de Eventos',
            ],
            [
                'id' => 8,
                'election_id' => 1,
                'name' => 'Gewandmeister',
                'info' => 'Maestro de Condecoraciones',
            ]
        ];

        Position::insert($positions);
    }
}

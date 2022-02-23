<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ranks = [
            [
                'id' => 1,
                'name' => 'Oberzunftmeister',
                'info' => 'Presidente',
                'rank' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Zeremonienmeister',
                'info' => 'Maestro de Ceremonias',
                'rank' => 2,
            ],
            [
                'id' => 3,
                'name' => 'Jokilimeister',
                'info' => 'Maestro Jokili',
                'rank' => 3,
            ],
            [
                'id' => 4,
                'name' => 'Zunftzackelmeister',
                'info' => 'Tesorero',
                'rank' => 4,
            ],
            [
                'id' => 5,
                'name' => 'Programm-Meister',
                'info' => 'Maestro de Programas',
                'rank' => 5,
            ],
            [
                'id' => 6,
                'name' => 'Torbuchmeister',
                'info' => 'Secretario',
                'rank' => 6,
            ],
            [
                'id' => 7,
                'name' => 'Stroossfasnetmeister',
                'info' => 'Maestro de Eventos',
                'rank' => 7,
            ],
            [
                'id' => 8,
                'name' => 'Gewandmeister',
                'info' => 'Maestro de Condecoraciones',
                'rank' => 8,
            ],
            [
                'id' => 9,
                'name' => 'Zunftmeister',
                'info' => null,
                'rank' => 9,
            ],
            [
                'id' => 10,
                'name' => 'Jokili',
                'info' => null,
                'rank' => 1,
            ],
            [
                'id' => 11,
                'name' => 'Aprendiz Jokili',
                'info' => null,
                'rank' => 11,
            ],
            [
                'id' => 12,
                'name' => 'Aspirante Jokili',
                'info' => null,
                'rank' => 12,
            ],
            [
                'id' => 13,
                'name' => 'Subscriptor',
                'info' => null,
                'rank' => 13,
            ],
        ];

        Rank::insert($ranks);
    }
}

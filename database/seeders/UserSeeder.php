<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)
        ->has(UserData::factory()->state([
            'avatar' => '/storage/factory/avatar/misc/stormtrooper.jpg',
            'phone' => '+58 412 000 0000',
            'site' => 'juliobergman.com',
            'country' => 'VE',
            'city' => 'Colonia Tovar',
            'address' => 'Cerrada Gutt',
            'citizenship' => 'VE',
            'id_prefix' => 'V-',
            'id_number' => '18.816.816',
            'occupation' => 'Desarrollador',
            'gender' => 'male',
            'birth_at' => '1988-07-27',
            'birthplace' => 'Caracas',
            'godfather' => null,
            'number' => 63,
            'position' => 'Jokili',
            'zunftrat_in' => '2013-02-02',
            'zunftrat_out' => '2018-04-01',
            'member_since' => '1994-02-02',
            'mask' => true,
            'status' => 1,
        ]))
        ->state([
            'id' => 1,
            'first_name' => env('OWNER_FIRST_NAME'),
            'last_name' => env('OWNER_LAST_NAME'),
            'email' => env('OWNER_EMAIL'),
            'email_verified_at' => null,
            'role' => 'superadmin',
            'password' => Hash::make(env('OWNER_PASSWORD')),
            'verified' => true,
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])
        ->create();

        User::factory(6)
        ->has(UserData::factory())
        ->state([
            'role' => 'subscriber',
        ])
        ->create();

        User::factory(2)
        ->has(UserData::factory())
        ->state([
            'role' => 'applicant',
        ])
        ->create();

        User::factory(5)
        ->has(UserData::factory())
        ->state([
            'role' => 'member',
        ])
        ->create();

        User::factory(2)
        ->has(UserData::factory())
        ->state([
            'role' => 'admin',
        ])
        ->create();














        DB::table('personal_access_tokens')->insert([
            'id' => 1,
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => 1,
            'name' => 'MainToken',
            'token' => 'fa842a78e1c227cf10a86a0364214a4e8e6acc6e3ab0a003c1cdf6197d562ad1',
            'abilities' => '["server:admin"]',
            'last_used_at' => now(),
        ]);
    }
}

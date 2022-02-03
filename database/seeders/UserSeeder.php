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
            'country' => 'VE',
        ]))
        ->state([
            'id' => 1,
            'name' => env('OWNER_NAME'),
            'email' => env('OWNER_EMAIL'),
            'email_verified_at' => now(),
            'password' => Hash::make(env('OWNER_PASSWORD')),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
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

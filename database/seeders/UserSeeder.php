<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Faker\Generator;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = \Faker\Factory::create();
        
        User::factory(1)
        ->has(UserData::factory()->state([
            'avatar' => '/storage/factory/avatar/misc/stormtrooper.jpg',
            'phone' => '+58 412 000 0000',
            'country' => 'VE',
            'city' => 'Colonia Tovar',
            'address' => 'Cerrada Gutt',
            'citizenship' => 'VE',
            'id_prefix' => 'V-',
            'id_number' => '09.465.489',
            'occupation' => 'Desarrollador',
            'gender' => 'male',
            'birth_at' => '1990-01-27',
            'birthplace' => 'Caracas',
            'godfather' => null,
            'number' => 58,
            'rank' => 9,
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
            'email_verified_at' => now(),
            'role' => 'admin',
            'password' => Hash::make(env('OWNER_PASSWORD')),
            'verified' => true,
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])
        ->create();

        // Test Api Token
        DB::table('personal_access_tokens')->insert([
            'id' => 1,
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => 1,
            'name' => 'MainToken',
            'token' => 'fa842a78e1c227cf10a86a0364214a4e8e6acc6e3ab0a003c1cdf6197d562ad1',
            'abilities' => '',
            'last_used_at' => now(),
        ]);

        // Members
    
        User::factory(99)
        ->has(
            UserData::factory()
            ->state(new Sequence(
                ['gender' => null],
                ['gender' => 'male'],
                ['gender' => 'female'],
                ['gender' => 'male'],
                ['gender' => 'female'],
            ))
            ->state(new Sequence(
                ['country' => 'VE'],
                ['country' => 'VE'],
                ['country' => 'DE'],
                ['country' => 'US'],
            ))
            ->state(new Sequence(
                ['avatar' => '/storage/factory/avatar/male/avatar-1.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-1.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-2.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-2.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-3.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-3.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-4.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-4.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-5.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-5.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-6.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-6.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-7.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-7.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-8.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-8.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-9.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-9.jpg'],
                ['avatar' => '/storage/factory/avatar/male/avatar-10.jpg'],
                ['avatar' => '/storage/factory/avatar/female/avatar-10.jpg'],
            ))
  
        )
        ->state(new Sequence(
            ['role' => 'subscriber'],
            ['role' => 'subscriber'],
            ['role' => 'applicant'],
            ['role' => 'member'],
            ['role' => 'member'],
            ['role' => 'member'],
            ['role' => 'member'],
            ['role' => 'member'],
            ['role' => 'admin'],
        ))
        ->create();
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $users = [
            '0' => [
                'name' => 'Filip Filipović',
                'email' => 'filip@mail.com',
                'password' => Hash::make('12345678'),
                'position_id' => 1,
                'role_id' => 1,
            ], 
            '1' => [
                'name' => 'Milica Milicic',
                'email' => 'milica@mail.com',
                'password' => Hash::make('12345678'),
                'position_id' => 2,
                'role_id' => 2,
            ],
            '2' => [
                'name' => 'Ivana Milicic',
                'email' => 'ivana@mail.com',
                'password' => Hash::make('12345678'),
                'position_id' => 2,
                'role_id' => 5,
            ]
        ];

        foreach($users as $user) {
            User::query()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'], 
                'position_id' => $user['position_id'],
                'role_id' => $user['role_id'] 
            ]);
        }
    }
}

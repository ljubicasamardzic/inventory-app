<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DepartmentSeeder::run();
        PositionSeeder::run();
        RoleSeeder::run();
        UserSeeder::run();
        EquipmentCategorySeeder::run();
        TicketStatusSeeder::run();
    }
}

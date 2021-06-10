<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $positions = [
            ['name' => 'Frontend Developer', 'department_id' => 1],
            ['name' => 'Backend Developer', 'department_id' => 1]
        ];
        foreach ($positions as $position) {
            Position::query()->create($position);
        }
    }
}

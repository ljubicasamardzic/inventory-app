<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{

    public static function run() {
        $departments = ['Delivery', 'HR', 'Marketing'];

        foreach($departments as $d) {
            Department::query()->create([
                'name' => $d
            ]);
        }
    }
}

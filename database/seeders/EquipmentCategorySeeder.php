<?php

namespace Database\Seeders;

use App\Models\EquipmentCategory;
use Illuminate\Database\Seeder;

class EquipmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $data = ['Monitor', 'Laptop', 'Kućište', 'Tastatura', 'Miš'];
        foreach ($data as $category) {
            EquipmentCategory::query()->create(['name' => $category]);
        }
    }
}

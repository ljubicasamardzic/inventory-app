<?php

namespace Database\Seeders;

use App\Models\RequestStatus;
use Illuminate\Database\Seeder;

class RequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $statuses = [
            '1' => ['name' => 'Pending', 'icon' => 'badge-warning'],
            '2' => ['name' => 'Approved', 'icon' => 'badge-success'],
            '3' => ['name' => 'Rejected', 'icon' => 'badge-danger']
        ];

        foreach ($statuses as $key => $status) {
            RequestStatus::query()->create([
                'id' => $key,
                'name' => $status['name'],
                'icon' => $status['icon']
            ]);
        }
    }
}

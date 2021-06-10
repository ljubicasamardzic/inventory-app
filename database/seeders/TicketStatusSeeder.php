<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $statuses = [
            '1' => ['name' => 'Unprocessed', 'icon' => 'badge-warning'],
            '2' => ['name' => 'In progress', 'icon' => 'badge-info'],
            '3' => ['name' => 'Approved', 'icon' => 'badge-success'],
            '4' => ['name' => 'Rejected', 'icon' => 'badge-danger']
        ];

        foreach ($statuses as $key => $status) {
            TicketStatus::query()->create([
                'id' => $key,
                'name' => $status['name'],
                'icon' => $status['icon']
            ]);
        }
    }
}

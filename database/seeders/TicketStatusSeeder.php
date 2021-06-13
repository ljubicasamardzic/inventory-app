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
            '3' => ['name' => 'Waiting for equipment', 'icon' => 'badge-info'],
            '4' => ['name' => 'Processed', 'icon' => 'badge-success']
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

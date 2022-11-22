<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketStatus::create([
            'code' => 'opn',
            'status' => 'Open'
        ]);
        TicketStatus::create([
            'code' => 'cld',
            'status' => 'Closed'
        ]);
        TicketStatus::create([
            'code' => 'asn',
            'status' => 'Assigned'
        ]);
    }
}

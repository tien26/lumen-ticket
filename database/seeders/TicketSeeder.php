<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            Ticket::create([
                'ticket_title' => 'ini adalah ticket ' . $i,
                'user_id' => rand(1, 5),
                'ticket_msg' => '<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Neque aliquam odit quasi impedit ad provident officia incidunt repellendus quae, excepturi beatae, odio laborum. Illum quo earum porro tempore vel autem</p>',
                'status' => 1
            ]);
        }
    }
}

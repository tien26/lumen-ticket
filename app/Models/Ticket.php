<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = ['id'];

    public function status_ticket()
    {
        return $this->belongsTo(TicketStatus::class, 'status', 'id');
    }
}

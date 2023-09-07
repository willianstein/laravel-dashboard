<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessages extends Model {
    use HasFactory;

    protected $table = "ticket_messages";

    protected $fillable = ['ticket_id','requester_id','responsible_id','origin','message','type'];

    public function requester() {
        return $this->belongsTo(User::class,'requester_id','id');
    }

    public function responsible() {
        return $this->belongsTo(User::class, 'responsible_id','id');
    }
}

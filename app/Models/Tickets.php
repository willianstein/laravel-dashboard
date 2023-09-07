<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model {
    use HasFactory;

    protected $table = "tickets";

    protected $fillable = ['category_id','partner_id','requester_id','responsible_id','origin','initial_care_at','ended_in','status'];

    public const STATUSES = [
        'aberto'        => 'Aberto',
        'em andamento'  => 'Em Andamento',
        'finalizado'    => 'Finalizado'
    ];

    /**
     * RETORNA O STATUS DO TICKET
     * @param string $state
     * @return string|null
     */
    public static function status(string $state):? string {
        return (array_search($state,self::STATUSES)??null);
    }

    public function category() {
        return $this->belongsTo(TicketCategories::class,'category_id','id');
    }

    public function requester() {
        return $this->belongsTo(User::class,'requester_id','id');
    }

    public function responsible() {
        return $this->belongsTo(User::class, 'responsible_id','id');
    }

    public function partner() {
        return $this->belongsTo(Partners::class, 'partner_id','id');
    }
}

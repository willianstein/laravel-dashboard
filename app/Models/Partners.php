<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Partners extends Model {

    use HasApiTokens, HasFactory;

    protected $table = "partners";

    public const TYPES = [
        'Cliente' => 'Cliente',
        'Fornecedor' => 'Fornecedor'
    ];

    public const PERSON = [
        'Fisica' => 'Fisica',
        'Juridica' => 'Juridica'
    ];

    protected $fillable = [
        'name', 'trade_name', 'person', 'document01', 'document02', 'phone', 'email', 'type', 'segment', 'active', 'obs'
    ];

    public function address() {
        return $this->hasMany(Addresses::class, 'partner_id');
    }

    public function ranges() {
        return $this->belongsToMany(TransportRanges::class,'partners_ranges');
    }

     /**
     * RETORNA A USUARIO
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function partner() {
        return $this->hasOne(User::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *  MODEL ABSTRATA DE PEDIDOS
 */
class Orders extends Model {
    use HasFactory;

    /**
     * NOME DA TABELA
     * @var string
     */
    protected $table = "orders";

    /**
     * TIPOS DE PEDIDOS
     */
    public const TYPES = [
        'entrada'   => 'Entrada',
        'saida'     => 'Saída',
        'reversa'   => 'Reversa'
    ];

    /**
     * TIPOS DE PEDIDOS
     */
    public const SUPERVISORSIDS = [
        1,8,9
    ];

    /**
     * CONFIGURAÇÃO DOS FILLABLES DO LARAVEL
     * @var string[]
     */
    protected $fillable = [
        'type','office_id','partner_id','recipient_id','invoice','content_declaration','observations','status','forecast','third_system','third_system_id'
    ];

    /**
     * RETORNA O KEY DA CONSTANTE TYPE
     * @param string $name
     * @return string|null
     */
    public static function type(string $name):? string {
        return (array_search($name,self::TYPES)??null);
    }

    /**
     * RETORNA A UNIDADE
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function office() {
        return $this->hasOne(Offices::class, 'id','office_id');
    }

    /**
     * RETORNA O PARCEIRO
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function partner() {
        return $this->hasOne(Partners::class, 'id','partner_id');
    }

    /**
     * RETORNA O DESTINATARIO
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function recipient() {
        return $this->hasOne(Recipients::class, 'id','recipient_id');
    }

    /**
     * RETORNA DADOS DO TRANSPORTE
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transport() {
        return $this->hasOne(Transports::class, 'id','transport_id');
    }

    /**
     * RETORNA OS VOLUMES
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function volumes() {
        return $this->hasMany(OrderPackages::class, 'order_id','id');
    }

    public function hasIntegration() {
        return $this->belongsTo(Integrations::class, 'partner_id','partner_id');
    }
}

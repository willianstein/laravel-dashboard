<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transports extends Model {
    use HasFactory;

    protected $table = "transports";

    public const MODALITY = [
        'CIF' => 'CIF',
        'FOB' => 'FOB'
    ];

    public const PACKAGING = [
        'paletizado'    => 'Paletizado',
        'encaixotado'   => 'Encaixotado',
        'fracionado'    => 'Fracionado'
    ];

    protected $fillable = [
        'modality','carrier_name','packaging','driver','driver_document','car_model','car_type','car_plate',
    ];

    /**
     * RETORNA O KEY DA CONSTANTE MODALIDADE
     * @param string $name
     * @return string|null
     */
    public static function modality(string $name):? string {
        return (array_search($name,self::MODALITY)??null);
    }

    /**
     * RETORNA O KEY DA CONSTANTE ACONDICIONAMENTO
     * @param string $name
     * @return string|null
     */
    public static function packaging(string $name):? string {
        return (array_search($name,self::PACKAGING)??null);
    }

    /**
     * RETORNA ACONDICIONAMENTO
     * @return string
     */
    public function getPackaging() {
        return self::PACKAGING[$this->packaging];
    }

    /**
     * RETORNA MODALIDADE
     * @return string
     */
    public function getModality() {
        return self::MODALITY[$this->modality];
    }

    /**
     * VERIFICA SE ALGUNS DETERMINADOS CAMPOS
     * ESTÃO PREENCHIDOS
     * @return bool
     */
    public function registrationIsComplete() {
        // $fields = ['modality','carrier_name','packaging','driver','driver_document','car_model','car_type','car_plate'];
        $fields = ['modality','packaging'];
        $return = true;
        foreach ($fields as $field){
            if(trim($this->$field) == "" || $this->$field == null){
                $return = false;
            }
        }
        return $return;
    }
}

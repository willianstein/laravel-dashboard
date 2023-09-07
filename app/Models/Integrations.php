<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integrations extends Model {
    use HasFactory;

    protected $table = "integrations";

    protected $fillable = ['partner_id','third_system_name','url','user','password','token'];

    public function partner() {
        return $this->belongsTo(Partners::class,'partner_id','id');
    }
}

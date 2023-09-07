<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationItems extends Model {
    use HasFactory;

    protected $table = "integration_items";

    protected $fillable = ['integration_id','type','params'];

    public function integration() {
        return $this->belongsTo(Integrations::class,'integration_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTransportProviders extends Model {

    use HasFactory;

    protected $table = "tag_transport_providers";

    protected $fillable = ['partner_id','url','user','password','token','metadata'];

    public function metaData():object {
        return json_decode($this->metadata);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerServices extends Model {

    use HasFactory;

    protected $table = "partner_services";

    protected $fillable = ['partner_id','service_id','price','active'];

    public function service() {
        return $this->belongsTo(Services::class,'service_id','id');
    }

    public function currentPrice(int $partner_id, int $service_id) {

        $customPrice = PartnerServices::where('partner_id',$partner_id)->where('service_id',$service_id)->first();
        $fixedPrice = Services::find($service_id);
        return (empty($customPrice->price) ? $fixedPrice->price : $customPrice->price);

    }
}

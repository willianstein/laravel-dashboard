<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Products extends Model {
    use HasFactory;

    protected $table = "products";

    protected $fillable = [
        'isbn','title','publisher','category','synopsis','height','width','length','weight','cover','active'
    ];

    public function getCoverUrlAttribute() {
        if(filter_var($this->cover, FILTER_VALIDATE_URL)){
            return $this->cover;
        } else {
            return Storage::disk('public')->url($this->cover);
        }
    }

}

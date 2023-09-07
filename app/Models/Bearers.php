<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;

class Bearers extends Model {
    use HasFactory;

    protected $table = 'bearers';

    protected $fillable = ['token_id','token'];

    public function token() {
        $this->belongsTo(PersonalAccessToken::class, 'token_id');
    }
}

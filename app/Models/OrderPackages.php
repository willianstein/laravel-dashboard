<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPackages extends Model {
    use HasFactory;

    protected $table = "order_packages";

    protected $fillable = ['order_id','package_id','quantity','origin'];

    public const ORIGIN = [
        'proprio'   => 'Próprio',
        'terceiro'  => 'Terceiro'
    ];

    public static function origin(string $name):? string {
        return (array_search($name,self::ORIGIN)??null);
    }

    public function package() {
        return $this->belongsTo(Packages::class);
    }

}

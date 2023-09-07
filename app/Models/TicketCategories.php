<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategories extends Model {

    use HasFactory;

    protected $table = "ticket_categories";

    protected $fillable = ['name', 'active'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nominee extends User
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'user_id',
    ];

}

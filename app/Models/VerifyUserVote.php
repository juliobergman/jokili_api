<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyUserVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'election_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

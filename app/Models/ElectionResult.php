<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position_id',
        'nominee_id',
        'election_id',
    ];

    protected $casts = [
        'user_verified' => 'boolean'
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}

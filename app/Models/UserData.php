<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserData extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'profile_pic',
        'phone',
        'country',
        'city',
        'address',
        'gender',
        'site',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

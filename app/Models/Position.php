<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\UserController;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'name',
        'info',
    ];

    public function nominees()
    {
        
        $select_user = (new UserController)->data_select;
        return $this->hasManyThrough(
            User::class,
            Nominee::class,
            'position_id', // Foreign key on the nominee table...
            'id', // Foreign key on the user table...
            'id', // Local key on the positions table...
            'user_id' // Local key on the nominee table...
        )
        ->join('user_data', 'users.id', '=', 'user_data.user_id')
        ->leftJoin('countries', 'user_data.country', '=', 'countries.iso2')
        ->select($select_user);
    }

}

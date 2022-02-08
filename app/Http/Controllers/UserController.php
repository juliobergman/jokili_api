<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $data_select = [
            // Users
            'users.id',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.email_verified_at',
            // UserData
            'user_data.*',
            'user_data.site',
            'user_data.phone',
            'user_data.country',
            'user_data.city',
            'user_data.address',
            'user_data.gender',
            'user_data.avatar',
            // Country
            'countries.name as country_name',
        ];

        $uq = User::query();
        // Where
        $uq->where('users.id', $user->id);
        // Selects
        $uq->select($data_select);
        // Join
        $uq->join('user_data', 'users.id', '=', 'user_data.user_id');
        $uq->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        $user = $uq->first();

        // Display Name
        $dname = explode(' ', $user->first_name);
        $dlast = explode(' ', $user->last_name);
        $displayName = $dname[0].' '.$dlast[0];
        
        return $user;

    }
}

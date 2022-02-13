<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    
    public $data_select = [
        // Users
        'users.id',
        'users.first_name',
        'users.last_name',
        'users.email',
        'users.email_verified_at',
        'users.verified',
        'users.role',
        // UserData
        'user_data.*',
        // Country
        'countries.name as country_name',
        'countries.region as country_region',
        'countries.subregion as country_subregion',
        'countries.latitude as country_latitude',
        'countries.longitude as country_longitude',
    ];
    
    
    public function show(User $user)
    {

        
        $select = [
            'godfathers.first_name as godfather_first_name',
            'godfathers.last_name as godfather_last_name',
        ];
        
        $uq = User::query();
        // Where
        $uq->where('users.id', $user->id);
        // Selects
        $uq->select(array_merge($this->data_select, $select));
        // 
        $uq->with('elections');
        // Join
        $uq->join('user_data', 'users.id', '=', 'user_data.user_id');
        $uq->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        $uq->leftJoin('users as godfathers', 'user_data.godfather', '=', 'godfathers.id');
        $user = $uq->first();

        // Display Name
        $dname = explode(' ', $user->first_name);
        $dlast = explode(' ', $user->last_name);
        $displayName = $dname[0].' '.$dlast[0];
        
        return $user;

    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required'],
        ]);

        $update = [
            'user' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role' => $request->role,
                'verified' => $request->verified,
            ],
            'userdata' => [
                'phone' => $request->phone,
                'site' => $request->site,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'citizenship' => $request->citizenship,
                'id_prefix' => $request->id_prefix,
                'id_number' => $request->id_number,
                'occupation' => $request->occupation,
                'gender' => $request->gender,
                'birth_at' => $request->birth_at,
                'birthplace' => $request->birthplace,
                'number' => $request->number,
                'position' => $request->position,
                'member_since' => $request->member_since,
                'mask' => $request->mask,
                'godfather' => $request->godfather,
                'zunftrat_in' => $request->zunftrat_in,
                'zunftrat_out' => $request->zunftrat_out,
                'status' => $request->status,
                'avatar' => $request->avatar,
            ],
        ];

        $updated = User::where('id', $user->id)->update($update['user']);

        if($updated){
            $dataupdated = UserData::where('user_id', $user->id)->update($update['userdata']);
            if ($dataupdated) {

                $ruser = $this->show($user);
                return new JsonResponse(['message' => 'User Successfully Updated', 'user' => $ruser], 200);
            }
        }
        return new JsonResponse(['message' => 'Request Failed to Complete'], 422);

    
    }

    public function godfathers()
    {
        $gf = User::query();

        // Selects
        $gf->select($this->data_select);
        // where
        $gf->whereNotIn('users.id', [Auth::user()->id]);
        $gf->whereIn('users.role', ['member','admin','superadmin']);
        // Join
        $gf->join('user_data', 'users.id', '=', 'user_data.user_id');
        $gf->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        $gf->leftJoin('users as godfathers', 'user_data.godfather', '=', 'godfathers.id');
        // Order
        $gf->orderBy('users.id','ASC');


        return $gf->get();
    }

}

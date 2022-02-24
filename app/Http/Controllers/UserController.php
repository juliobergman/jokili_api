<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Rank;
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
        // Rank',
        'ranks.name as rank_name',
        // Country
        'countries.name as country_name',
        'countries.region as country_region',
        'countries.subregion as country_subregion',
        'countries.latitude as country_latitude',
        'countries.longitude as country_longitude',
    ];

    public function role(Request $request, $role = null)
    {
        
        $user = $request->user();


        

        $uq = User::query();
        // Where
        $uq->where('users.id', '!=', $user->id);
        $uq->where('users.role', '!=', 'superadmin');
        if($user->role == 'subscriber' || $user->role == 'applicant'){
            return new JsonResponse(['message' => 'Access Denied'], 401);
        }
        if($user->role == 'member'){
            $uq->where('users.role', '!=', 'subscriber');
            $uq->where('users.role', '!=', 'applicant');
        }
        if($role){
            $uq->where('role', $role);
        }
        // Selects
        $uq->select($this->data_select);
        // Join
        $uq->join('user_data', 'users.id', '=', 'user_data.user_id');
        $uq->join('ranks', 'user_data.rank', '=', 'ranks.id');
        $uq->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        
        $uq->orderBy('users.last_name');

        // $uq->limit(8);

        $members = $uq->get();
        
        return $members;
    }

    public function group(Request $request, $group = 'role')
    {
        $data = collect($this->role($request));
        $total_users = $data->count();
        $grouped = $data->groupBy($group);

        foreach ($grouped as $key => $value) {
            $users[$key] = $value;
            $users[$key.'_count'] = $value->count();
            $users[$key.'_ratio'] = $value->count() / $total_users;
        }
        $users['total'] = $total_users;

        return $users;
    }
    
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
        $uq->join('ranks', 'user_data.rank', '=', 'ranks.id');
        $uq->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        $uq->leftJoin('users as godfathers', 'user_data.godfather', '=', 'godfathers.id');
        $user = $uq->first();
        
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
                'rank' => $request->rank,
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

    public function statistics(Request $request)
    {
        $uq = User::query();

        // Selects
        $uq->select($this->data_select);
        // Join
        $uq->join('user_data', 'users.id', '=', 'user_data.user_id');
        $uq->join('ranks', 'user_data.rank', '=', 'ranks.id');
        $uq->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        $uq->leftJoin('users as godfathers', 'user_data.godfather', '=', 'godfathers.id');
        // Order
        $uq->orderBy('users.id','ASC');

        $users = collect($uq->get());
        $total_users = $users->count();

        // By Rank
        $userRanks = Rank::where('id', '>', 9)->get();
        $slot = 0;
        foreach ($userRanks as $rank) {
            $by_rank[$slot]['name'] = $rank->name;
            $by_rank[$slot]['value'] = $users->where('rank', $rank->id)->count();
            $by_rank[$slot]['ratio'] = $users->where('rank', $rank->id)->count() / $total_users;
            $by_rank[$slot]['dynamic'] = true;
            $slot++;
        }

        // By Country
        $userCountries = $users->groupBy('country');
        $slot = 0;
        foreach ($userCountries as $key => $country) {

            // $slot = $country[0]['country_name'];

            $by_country[$slot]['iso'] = $key;
            $by_country[$slot]['iso_lc'] = strtolower($key);
            $by_country[$slot]['name'] = $country[0]['country_name'];
            $by_country[$slot]['value'] = $users->where('country', $key)->count();
            $by_country[$slot]['ratio'] = $users->where('country', $key)->count() / $total_users;
            $by_country[$slot]['dynamic'] = true;

            $slot++;
        }

        $statistics = [
            'by_role' => [
                [
                    'name' => 'subscriber',
                    'value' => $users->where('role', 'subscriber')->count(),
                    'ratio' => $users->where('role', 'subscriber')->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'cyan',
                ],
                [
                    'name' => 'applicant',
                    'value' => $users->where('role', 'applicant')->count(),
                    'ratio' => $users->where('role', 'applicant')->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'amber',
                ],
                [
                    'name' => 'member',
                    'value' => $users->where('role', 'member')->count(),
                    'ratio' => $users->where('role', 'member')->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue',
                ],
                [
                    'name' => 'admin',
                    'value' => $users->where('role', 'admin')->count(),
                    'ratio' => $users->where('role', 'admin')->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'indigo',
                ],
            ],
            'by_status' => [
                [
                    'name' => 'active',
                    'value' => $users->where('status', 1)->count(),
                    'ratio' => $users->where('status', 1)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'accent'
                ],
                [
                    'name' => 'inactive',
                    'value' => $users->where('status', 2)->count(),
                    'ratio' => $users->where('status', 2)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'indigo-4'
                ],
                [
                    'name' => 'suspended',
                    'value' => $users->where('status', 3)->count(),
                    'ratio' => $users->where('status', 3)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'deep-orange'
                ],
                [                    
                    'name' => 'deceased',
                    'value' => $users->where('status', 4)->count(),
                    'ratio' => $users->where('status', 4)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'grey-8'
                ],
            ],
            'by_rank' => $by_rank,
            'by_verified' => [
                [
                    'name' => 'verified',
                    'value' => $users->where('verified', true)->count(),
                    'ratio' => $users->where('verified', true)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'accent',
                ],
                [
                    'name' => 'not_verified',
                    'value' => $users->where('verified', false)->count(),
                    'ratio' => $users->where('verified', false)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'negative'
                ]
            ],
            'by_verified_email' => [
                [
                    'name' => 'verified',
                    'value' => $users->where('email_verified', true)->count(),
                    'ratio' => $users->where('email_verified', true)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'accent',
                ],
                [
                    'name' => 'not_verified',
                    'value' => $users->where('email_verified', false)->count(),
                    'ratio' => $users->where('email_verified', false)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'negative'
                ]
            ],
            'by_mask' => [
                [
                    'name' => 'with_mask',
                    'value' => $users->where('mask', true)->count(),
                    'ratio' => $users->where('mask', true)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'accent',
                ],
                [
                    'name' => 'without_mask',
                    'value' => $users->where('mask', false)->count(),
                    'ratio' => $users->where('mask', false)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'negative'
                ]
            ],
            'by_abroad' => [
                [
                    'name' => 'in_venezuela',
                    'value' => $users->where('abroad', false)->count(),
                    'ratio' => $users->where('abroad', false)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'accent',
                ],
                [
                    'name' => 'abroad',
                    'value' => $users->where('abroad', true)->count(),
                    'ratio' => $users->where('abroad', true)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'amber'
                ]
            ],
            'by_country' => $by_country,
            'by_gender' => [
                [
                    'name' => 'unknown',
                    'value' => $users->where('gender', null)->count(),
                    'ratio' => $users->where('gender', null)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'deep-purple'
                ],
                [
                    'name' => 'male',
                    'value' => $users->where('gender', 'male')->count(),
                    'ratio' => $users->where('gender', 'male')->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue',
                ],
                [
                    'name' => 'female',
                    'value' => $users->where('gender', 'female')->count(),
                    'ratio' => $users->where('gender', 'female')->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'pink'
                ],
            ],
            'by_legal_age' => [
                [
                    'name' => 'legal',
                    'value' => $users->where('age', '<=', 17)->count(),
                    'ratio' => $users->where('age', '<=', 17)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'accent',
                ],
                [
                    'name' => 'not_legal',
                    'value' => $users->where('age', '>=', 18)->count(),
                    'ratio' => $users->where('age', '>=', 18)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'negative'
                ]
            ],
            'by_age' => [
                [
                    'name' => 'range0_10',
                    'value' => $users->where('age', '<=', 10)->count(),
                    'ratio' => $users->where('age', '<=', 10)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-5'
                ],
                [
                    'name' => 'range11_20',
                    'value' => $users->where('age', '<=', 20)->where('age', '>', 10)->count(),
                    'ratio' => $users->where('age', '<=', 20)->where('age', '>', 10)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-6'
                ],
                [
                    'name' => 'range21_30',
                    'value' => $users->where('age', '<=', 30)->where('age', '>', 20)->count(),
                    'ratio' => $users->where('age', '<=', 30)->where('age', '>', 20)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-7'
                ],
                [
                    'name' => 'range31_40',
                    'value' => $users->where('age', '<=', 40)->where('age', '>', 30)->count(),
                    'ratio' => $users->where('age', '<=', 40)->where('age', '>', 30)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-8'
                ],
                [
                    'name' => 'range41_50',
                    'value' => $users->where('age', '<=', 50)->where('age', '>', 40)->count(),
                    'ratio' => $users->where('age', '<=', 50)->where('age', '>', 40)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-9'
                ],
                [
                    'name' => 'range51_60',
                    'value' => $users->where('age', '<=', 60)->where('age', '>', 50)->count(),
                    'ratio' => $users->where('age', '<=', 60)->where('age', '>', 50)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-10'
                ],
                [
                    'name' => 'range61_70',
                    'value' => $users->where('age', '<=', 70)->where('age', '>', 60)->count(),
                    'ratio' => $users->where('age', '<=', 70)->where('age', '>', 60)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-11'
                ],
                [
                    'name' => 'range71_80',
                    'value' => $users->where('age', '<=', 80)->where('age', '>', 70)->count(),
                    'ratio' => $users->where('age', '<=', 80)->where('age', '>', 70)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-12'
                ],
                [
                    'name' => 'range81_90',
                    'value' => $users->where('age', '<=', 90)->where('age', '>', 80)->count(),
                    'ratio' => $users->where('age', '<=', 90)->where('age', '>', 80)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-13'
                ],
                [
                    'name' => 'range91',
                    'value' => $users->where('age', '>', 90)->count(),
                    'ratio' => $users->where('age', '>', 90)->count() / $total_users,
                    'dynamic' => false,
                    'color' => 'blue-14'
                ]
            ],
            'total_users' => $total_users,

        ];


        return $statistics;
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
        $gf->join('ranks', 'user_data.rank', '=', 'ranks.id');
        $gf->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        $gf->leftJoin('users as godfathers', 'user_data.godfather', '=', 'godfathers.id');
        // Order
        $gf->orderBy('users.id','ASC');


        return $gf->get();
    }

}

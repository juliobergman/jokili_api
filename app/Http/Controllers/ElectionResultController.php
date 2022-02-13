<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\ElectionResult;
use App\Models\VerifyUserVote;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class ElectionResultController extends Controller
{
    public function submit(Request $request)
    {
        // Validation and Builder
        foreach ($request->votes as $key => $value) {
            $validator = Validator::make($value, [
                "user_id"    => "required|integer",
                "position_id"    => "required|integer",
                "nominee_id"    => "required|integer",
                "election_id"    => "required|integer",
            ]);

            if ($validator->fails()) {
                return new JsonResponse(['message' => 'Request Failed to Complete'], 422);
            }
            
            $votes[] = [
                'user_id' => $value['user_id'],
                'position_id' => $value['position_id'],
                'nominee_id' => $value['nominee_id'],
                'election_id' => $value['election_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // return $votes;

        $verify_vote = [
            'user_id' => $request->user()->id,
            'election_id' => $request->election_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // return $verify_vote;

        if(ElectionResult::insert($votes) && VerifyUserVote::insert($verify_vote)){
            return new JsonResponse(['message' => 'success'], 201);
        }
        return new JsonResponse(['message' => 'Request Failed to Complete'], 422);
    
    }

    public function results(Request $request)
    {

        $vt = ElectionResult::query();
        // Where
        $vt->where('election_results.election_id', 1);
        // Select
        $vt->select([
            'election_results.*',
            'positions.name as position_name',
            'voter.verified as user_verified',
            'users.first_name as first_name',
            'users.last_name as last_name',
            'user_data.avatar as avatar',
        ]);
        // Join
        $vt->join('positions', 'election_results.position_id', '=', 'positions.id');
        $vt->join('users', 'election_results.nominee_id', '=', 'users.id');
        $vt->join('users as voter', 'election_results.user_id', '=', 'voter.id');
        $vt->join('user_data', 'election_results.nominee_id', '=', 'user_data.user_id');
        // Order By
        $vt->orderBy('id');

        // $vt->limit(10);
        // Result Colection
        $votes = $vt->get();

        if(!$votes->count()) return new JsonResponse(['message' => 'No votes yet'], 202);
        
        
        
        
        
        $verified_votes = $votes->where('user_verified', true)->count();
        $unverified_votes = $votes->where('user_verified', false)->count();
        $position = Position::where('election_id', 1)->get();
        $noms = $votes->where('user_verified', true)->groupBy('nominee_id')->sortBy('nominee_id');
        foreach ($position as $k1 => $pos) {
            foreach ($noms as $k2 => $nom) {
                $nom_votes = $nom->where('position_id', $pos->id)->count();
                $positions[$pos->id]['position_id'] = $pos->id;
                $positions[$pos->id]['position_name'] = $pos->name;
                $positions[$pos->id]['position_info'] = $pos->info;
                if($nom_votes > 0){
                    $positions[$pos->id]['nominees'][] = [
                        'nominee_id' => $k2,
                        'nominee_full_name' => $nom->where('nominee_id', $k2)->first()->full_name,
                        'nominee_avatar' => $nom->where('nominee_id', $k2)->first()->avatar,
                        'total_votes' => $nom_votes,
                    ];
                }
                $positions[$pos->id]['total_votes'] = $votes->where('position_id', $pos->id)->where('user_verified', true)->count();
            }
        }

        $ret = [
            'election_results' => $positions,
            'verified_votes' => $verified_votes,
            'unverified_votes' => $unverified_votes,
            'total_votes' => $votes->count(),
        ];



        return $ret;
    }
}

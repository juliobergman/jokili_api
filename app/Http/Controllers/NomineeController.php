<?php

namespace App\Http\Controllers;

use App\Models\Nominee;
use App\Models\Position;
use Illuminate\Http\Request;

class NomineeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Position $position)
    {
        
        $import_select = (new UserController)->data_select;
        $nominee_select = [
            'nominees.id as nominee_id',
            'positions.id as position_id',
            'positions.name as position_name',
        ];
        $select = array_merge($nominee_select,$import_select);
        
        $nom = Nominee::query();
        // Where
        $nom->where('position_id', $position->id);
        // Selects
        $nom->select($select);
        // Join
        $nom->leftJoin('users', 'nominees.user_id', '=', 'users.id');
        $nom->join('user_data', 'users.id', '=', 'user_data.user_id');
        $nom->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        $nom->leftJoin('positions', 'nominees.position_id', '=', 'positions.id');
        // Order
        $nom->orderBy('users.id', 'asc');
        
        return $nom->get();
        
        return Nominee::where('position_id', $position->id)->get(); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Nominee  $nominee
     * @return \Illuminate\Http\Response
     */
    public function show(Nominee $nominee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Nominee  $nominee
     * @return \Illuminate\Http\Response
     */
    public function edit(Nominee $nominee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nominee  $nominee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nominee $nominee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nominee  $nominee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nominee $nominee)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index()
    {
        return Rank::query()->orderBy('rank')->get();
    }
}

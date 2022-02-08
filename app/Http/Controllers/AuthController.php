<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {   
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device' => ['required'],
        ]);
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            // Authorized
            $user = (new UserController)->show(Auth::user());
            return new JsonResponse([
                'message' => 'Authenticated', 
                'auth' => true,
                'token' => $user->createToken('$request->device')->plainTextToken,
                'user' => $user,
            ], 200);
        }
        // Not Authorized
        return new JsonResponse(['message' => 'Unauthenticated', 'auth' => false], 419);
        
        
    }

    public function auth(Request $request)
    {
        $user = (new UserController)->show(Auth::user());
        $tk = explode(' ', $request->header('Authorization'));
        return new JsonResponse([
            'message' => 'Authenticated',
            'auth' => true,
            'user' => $user,
            'token' => $tk[1],
        ], 200);
    }

}

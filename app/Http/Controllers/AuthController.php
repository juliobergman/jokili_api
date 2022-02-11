<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);



        
        $status = Password::sendResetLink(
            $request->only('email')
        );
        
        return $status === Password::RESET_LINK_SENT
                ? ['status' => __($status)]
                : ['email' => __($status)];
    }

    public function resetPassword($token)
    {
        return ['token' => $token];
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? ['status' => true, 'message' => __($status)]
                    : ['status' => false, 'message' => __($status)];
    }

    public function emailVerificationSend(Request $request)
    {   
        $request->user()->sendEmailVerificationNotification();
        return new JsonResponse([
            'status' => true,
            'message' => 'Verification Link Sent'
        ], 200);
    }

    public function emailVerificationVerify(EmailVerificationRequest $request)
    {
        return $request->fulfill();
    }

}

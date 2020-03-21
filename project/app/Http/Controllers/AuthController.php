<?php

namespace App\Http\Controllers;

use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;

class AuthController extends Controller
{
    // private function authenticated(Request $request, Authenticatable $user)
    // {
    //     if ($user->google2fa_secret) {
    //         Auth::logout();

    //         $request->session()->put('2fa:user:id', $user->id);

    //         return redirect('2fa/validate');
    //     }

    //     return redirect()->intended($this->redirectTo);
    // }

    // public function getValidateToken()
    // {
    //     if (session('2fa:user:id')) {
    //         return view('2fa/validate');
    //     }

    //     return redirect('login');
    // }

    public function postValidateToken(ValidateSecretRequest $request)
    {
        //get user id and create cache key
        $userId = $request->session()->pull('2fa:user:id');
        $key    = $userId . ':' . $request->totp;

        //use cache to store token to blacklist
        Cache::add($key, true, 4);

        //login and redirect user
        Auth::loginUsingId($userId);

        return redirect()->intended($this->redirectTo);
    }
}

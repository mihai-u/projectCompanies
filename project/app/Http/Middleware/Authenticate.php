<?php

namespace App\Http\Middleware;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\User;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function logout(Request $request){
        Auth::logout();
        return redirect('/login');
    }

    public function admin(Request $request){
        if($request->user() && $request->user()->type != 'admin'){
            return new Response(view('unauthorized')->with('role', 'ADMIN'));
        }
    }

    public function user(Request $request){
        if($request->user() && $request->user()->type != 'user'){
            return new Response(view('unauthorized')->with('role', 'USER'));
        }
    }
}

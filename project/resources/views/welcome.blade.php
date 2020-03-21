@extends('layouts.layout')

@section('title', 'Welcome')

@section('links')
<link rel="stylesheet" href="/css/welcome.css">
@endsection

@section('content')
    <div class="section">
        <div class="content">
                @if (Route::has('login'))
                <div class="">
                    @auth
                            <a href="{{ url('/home') }}">Home</a>
                        @else
                            <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif

                    @endauth
                    <a href="/logout">Logout</a>
                </div>
                @endif

                <div class="">
                    <div class="title m-b-md">
                        Project Companies
                    </div>
                </div>
        </div>
    </div>
@endsection

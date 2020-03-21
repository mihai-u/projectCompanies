@extends('layouts.layout')

@section('content')
<!-- <div>
    You cannot access this page! This is for only ‘{{$role}}’

</div> -->

    <div class="container ">
        <div class="row justify-content-center mt-5">
            
                <h1>Oops... It looks like you are not an {{$role}}</h1>
            
        </div>
        <div class="row justify-content-center mt-5">
                <img src="/img/hacker.svg" class="img-fluid">
        </div>
    </div>
@endsection

@extends('layouts.layout')

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/list.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="text-align:center;">List</div>
            </div>
        </div>
    </div>
</div>


<div class="container section-A">
        @foreach($companies as $company)
        <div class="card card-outer" style="width: 20rem;">
            <div class="card-title">
                <h5> <span class="badge badge-dark"> Username </span> {{ucwords($company->user->username)}} </span></h5>
            </div>

            <hr>

            <div class="card-title">
                <h5> <span class="badge badge-dark"> Company </span> {{ucwords($company->companyName)}} </span> </h5>
            </div>

            <hr>

            <div class="card-title">
                <h5> <span class="badge badge-dark"> Company Description </span> {{$company->companyDescription}} </span></h5>
            </div>

            <hr>

            <div class="card-title">
                <h5><span class="badge badge-success">Projects</span></h5>
            </div>
                        @foreach($company->project as $project)
                            <div class="card card-inner" style="width: 18rem;">
                                <div class="card-body">
                                  <h5 class="card-title"> <span class="badge badge-dark"> Title </span> {{$project->title}}</h5>
                                  <hr>
                                  <p class="card-text"><span class="badge badge-dark"> Description </span> {{$project->description}}</p>
                                </div>
                            </div>
                        @endforeach
        </div>
        @endforeach
</div>

<div class="container btn-container"><a href="{{ url('home') }}" class="btn btn-dark">Back</a></div>
@endsection

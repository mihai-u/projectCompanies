@extends('layouts.layout')

@section('title')
Projects
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="/css/projects.css">
@endsection

@section('content')
    <div class="container mt-5">

        <img src="{{ $company->photo }}" class="card-img-top">
        <h1> {{ strtoupper($company->companyName) }} </h1>
        <p>
            {{ $company->companyDescription }}
        </p>

        <div class="d-flex justify-content-center flex-wrap">
        @foreach($company->project as $project)
            <div class="p-2">
                <div class="card mt-5" style="width: 16rem;">
                    <img src="{{ $project->photo }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">{{ strtoupper($project->title) }}</h5>
                        <p class="card-text">{{ ucwords($project->description) }}</p>
                        <button class="btn btn-dark mr-2" onclick="window.location.href = 'projects/{{$project->id}}/edit';">Edit</a>
                        <button onclick="deleteProject({{ $company->id }}, {{ $project->id }})" href="projects/{{ $project->id }}/delete" type="submit" class="btn btn-outline-danger">Delete</button>
                    </div>
                 </div>
            </div>
        @endforeach
        </div>
        <a href="projects/add" class="btn btn-primary"> Add Project </a>
    </div>
@endsection

@section('scripts')

<script>
    function deleteProject(company_id, project_id){
        if(confirm("Are you sure?")){
            $.ajax({
            url: "projects/" + project_id + "/delete",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "DELETE",
            data: {},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })
        .done(function(response){
            window.location = "/companies";
        })
        }else{
            console.log("aborted");
        }
    }
</script>

@endsection

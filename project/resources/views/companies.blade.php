@extends('layouts.layout')

@section('title')
Companies
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/companies.css">
@endsection

@section('content')

    <div class="container">

        <h1> Companies </h1>

        <div class="d-flex justify-content-center flex-wrap">
        @foreach($companies as $company)
            <div class="p-2">
                <div class="card" style="width: 18rem;">
                    <img src="{{ $company->photo }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">{{ strtoupper($company->companyName) }}</h5>
                        <p class="card-text">{{ ucwords($company->companyDescription) }}</p>
                        <button class="btn btn-dark" onclick="window.location.href = 'companies/{{ $company->id }}/edit';">Edit</button>
                        <button class="btn btn-dark" onclick="window.location.href = 'companies/{{ $company->id }}/projects';">View Projects</button>
                        <button class="btn btn-outline-danger" onclick="deleteCompany({{ $company->id }})" type="submit">Delete</button>
                    </div>
                 </div>
            </div>
        @endforeach
        </div>
        <a href="companies/add" class="btn btn-primary"> Add Company </a>
    </div>
@endsection

@section('scripts')

<script>
    function deleteCompany(id){
        if(confirm("Are you sure?")){
            $.ajax({
            url: "companies/" + id + "/delete",
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

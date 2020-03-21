@extends('layouts.layout')

@section('title')
Add Company
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="/css/projects.css">
    <link rel="stylesheet" type="text/css" href="/css/edit.css">
@endsection

@section('content')
    <div class="container mt-5">
        <form>
            <div class="form-group">
                <input type="text" class="form-control" id="inputPhoto" placeholder="Photo URL">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="inputName" placeholder="Company Name" required>
                <div class="invalid-feedback text-left">
                    Please enter a company
                </div>
            </div>

            <div class="form-group">
                <textarea class="form-control" id="inputDescription" rows="3" placeholder="Company Description" required></textarea>
                <div class="invalid-feedback text-left">
                    Please enter a company
                </div>
            </div>
            <a class="btn btn-primary" role="button" href="#" onclick="updateInfo()">Submit</a>
          </form>
    </div>
@endsection

@section('scripts')
    <script>

    function updateInfo(){

        var newName = $('#inputName').val();
        var newDescription = $('#inputDescription').val();
        var newPhoto = $('#inputPhoto').val();

        $.ajax({
            url: "add",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {companyName: newName, companyDescription: newDescription, photo: newPhoto },
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){
            if(response.errorMessage == ''){
                window.location.href = "/companies";
            }else if(response.errorMessage == 'Company name cannot be null'){
                $('#inputName').addClass('is-invalid');
            }else if(response.errorMessage == 'Company description cannot be null'){
                $('#inputDescription').addClass('is-invalid');
            }
        })
        .fail(function(response){

        });
        }

    </script>
@endsection

@extends('layouts.layout')

@section('title')
Add Project
@endsection

@section('links')

@endsection

@section('content')
    <div class="container mt-5">
        <form>
            <div class="form-group">
                <input type="text" class="form-control" id="inputPhoto" placeholder="Photo URL">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="inputTitle" placeholder="Project Title">
                <div class="invalid-feedback text-left">
                    Please enter a title
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control" id="inputDescription" rows="3" placeholder="Project Description"></textarea>
                <div class="invalid-feedback text-left">
                    Please enter a description
                </div>
            </div>
            <a class="btn btn-primary" role="button" href="#" onclick="addProject()">Submit</a>
          </form>
    </div>
@endsection

@section('scripts')
    <script>

    function addProject(){

        var newTitle = $('#inputTitle').val();
        var newDescription = $('#inputDescription').val();
        var newPhoto = $('#inputPhoto').val();
        var companyId = $('#inputCompany').val();

        $.ajax({
            url: "add",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {title: newTitle, description: newDescription, photo: newPhoto, company: companyId},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){

            console.log(response.error);

            if(response.errorMessage == ''){
                window.location.href = "/companies";
            }else if(response.errorMessage == "titleEmpty"){
                $('#inputTitle').addClass('is-invalid');
            }else if(response.errorMessage == "descriptionEmpty"){
                $('#inputDescription').addClass('is-invalid');
            }
        })
        .fail(function(response){

        });
        }

    </script>
@endsection

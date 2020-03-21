@extends('layouts.layout')

@section('links')
    <!-- <link rel="stylesheet" type="text/css" href="/css/dashboard.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="/css/projects.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="/css/edit.css"> -->
    <link rel="stylesheet" type="text/css" href="/css/project-edit.css">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="image-container">
            <img id="photoUrl" src="{{ $project->photo }}" class="card-img-top">
            <a id="editPhoto" onclick="getProjectId({{ $project->id }})"><i class="fas fa-edit"></i></a>
        </div>

        <form>
            <div class="form-group">
                <input type="text" class="form-control" id="inputTitle" placeholder="{{ $project->title }}">
            </div>
            <div class="form-group">
                <textarea class="form-control" id="inputDescription" rows="3" placeholder="{{ $project->description }}"></textarea>
            </div>
            <a class="btn btn-primary" role="button" href="#" onclick="updateInfo({{ $project->id }})">Submit</a>
          </form>
    </div>
@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
    <script>

    var pr_id = "";

    function getProjectId(projectId){
        pr_id = projectId;
    }

    /*updates the project photo
     *Input params: url - the photo url entered by the user, we get it from the function below
     *Output: response.newPhotoUrl - it changes the content
     */
    function updatePhoto(url){

        // console.log(pr_id);

        var projectId = pr_id;
        var newPhotoUrl = url;

        if(url == ""){
            return false;
        }

        $.ajax({
            url: "edit/updatePhoto",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {photo: newPhotoUrl, id: projectId},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){
            $('#photoUrl').attr("src", response.newPhotoUrl);
        })
        .fail(function(response){

        });
}

    /*JS Framework for alert editing
     * The function is called on alert and calls updatePhoto
     * Input: #editPhoto - the id of the button/a tag
     * Output: alert
     * Bug: if the alert is closed without a link, a blank is stored in the db
     */

    $(document).on("click", "#editPhoto", function(e) {
        bootbox.prompt("Insert the link", function(result){
            if (result == null){
                return false;
            }
            updatePhoto(result);
        });
    });


    function updateInfo(pr_id){

        var projectId = pr_id;
        var newTitle = $('#inputTitle').val();
        var newDescription = $('#inputDescription').val();

        $.ajax({
            url: "edit/updateInfo",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {title: newTitle, description: newDescription, id: projectId},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){

                window.location.href = "/companies";

        })
        .fail(function(response){

        });
        }

    </script>
@endsection

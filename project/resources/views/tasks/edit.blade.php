@extends('layouts.layout')

@section('title')
Edit Task
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="/css/projects.css">
    <link rel="stylesheet" type="text/css" href="/css/edit.css">
@endsection

@section('content')
    <div class="container mt-5">
        <h1>Edit Task</h1>
        <form>
            <div class="form-group">
                <input type="text" class="form-control" id="inputTitle" placeholder="{{ $task->title }}">
            </div>
            <div class="form-group">
                <textarea class="form-control" id="inputDescription" rows="3" placeholder="{{ $task->description }}"></textarea>
            </div>

            <div class="form-group">
                <select class="form-control" id="inputStatus">
                  <option>Select Status</option>
                  <option>In Progress</option>
                  <option>Done</option>
                  <option>Paused</option>
                </select>
              </div>

            <div class="form-group">
                <select class="form-control" id="changeResponsible">
                    @foreach ($users as $user)
                        <option value="user{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
              </div>

            <a class="btn btn-primary" role="button" href="#" onclick="updateInfo({{ $task->id }})">Submit</a>
            <form action="/tasks/{{ $task->id }}/delete" method="POST">
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger"> Delete </button>
            </form>
          </form>

            
    </div>
@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
    <script>

    function updateInfo(task_id){

        var newTitle = $('#inputTitle').val();
        var newDescription = $('#inputDescription').val();
        var newStatus = $('#inputStatus').val();

        $.ajax({
            url: "edit/updateInfo",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {title: newTitle, description: newDescription, status: newStatus, id: task_id},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){
            window.location.href = "/tasks";
        })
        .fail(function(response){

        });
        }

    </script>
@endsection

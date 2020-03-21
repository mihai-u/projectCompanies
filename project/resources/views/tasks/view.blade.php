@extends('layouts.layout')

@section('title')
View Task
@endsection

@section('links')
    <!-- <link rel="stylesheet" type="text/css" href="/css/dashboard.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="/css/companies.css"> -->
    <link rel="stylesheet" type="text/css" href="/css/task-view.css">
@endsection

@section('content')
    <div class="container mt-5">

        @method('DELETE')

    <div class="card align-self-center" style="width:36rem;">
        <div class="card-body">
            <h5 class="card-title font-weight-bold text-center">{{ucwords($task->title)}}</h5>
            <p class="card-text">{{ ucwords($task->description) }}</p>
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                    <th scope="col">Owner</th>
                    <th scope="col">Responsible</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    @foreach($users as $user)
                        @switch($user)
                            @case($user->id == $task->owner_id)
                                <td> {{ lcfirst($user->name) }} </td>
                            @break
                            @case($user->id == $task->responsible_id)
                                <td> {{ lcfirst($user->name) }} </td>
                            @break
                        @endswitch
                    @endforeach
                    <td>{{ ucwords($task->status) }}</td>
                    </tr>
                </tbody>
            </table>
            <a href="edit" class="btn btn-dark">Edit</a>
            <button onclick="deleteTask()" href="/tasks/{{ $task->id }}/delete" type="submit" class="btn btn-danger">Delete</button>
        </div>
    </div>

    
@endsection

@section('scripts')
    <script>
        function deleteTask(){
            if(confirm("Are you sure?")){
                $.ajax({
                url: "delete",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "DELETE",
                data: {},
                cache: false,
                async: false,
                dataType: "json",
                processData: "false"
            })
            .done(function(response){
                window.location = "/tasks";
            })
            }else{
                console.log("aborted");
            }
        }
    </script>
@endsection

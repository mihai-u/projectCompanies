@extends('layouts.layout')

@section('title')
Tasks
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/tasks.css">
@endsection

@section('content')
<div class="alert alert-success" role="alert">
  Here you will see how much you have worked today
</div>
    <div class="container">

        <h1> Tasks </h1>

        <div class="row">
            <table class="table table-responsive">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Responsible</th>
                    <th scope="col">Owner</th>
                    <th scope="col">Project</th>
                    <th scope="col">Company</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $key=>$task)
                    <tr>
                        <th scope="row" class="align-middle">{{ ++$key }}</th>
                                <td class="align-middle">
                                    {{$task->title}}
                                </td>
                                <td class="align-middle">
                                    {{$task->description}}
                                </td>
                                <td class="align-middle">
                                    {{ $task->status }}
                                </td>
                                <td class="align-middle">
                                    @foreach($users as $user)
                                        @if($user->id == $task->responsible_id)
                                        {{ $user->name }}
                                        @endif
                                    @endforeach
                                </td>

                                <td class="align-middle">
                                        @foreach($users as $user)
                                        @if($user->id == $task->owner_id)
                                            {{ $user->name }}
                                        @endif
                                    @endforeach
                                </td>

                                <td class="align-middle">
                                     @foreach($projects as $project)
                                        @if($project->id == $task->project_id)
                                            {{ $project->title }}
                                        @endif
                                     @endforeach
                                </td>

                                <td class="align-middle">
                                    @foreach($companies as $company)
                                        @if($company->id == $task->company_id)
                                            {{ $company->companyName}}
                                        @endif
                                    @endforeach
                                </td>

                                <td class="align-middle">
                                    {{ date('d/m/Y', strtotime($task->created_at))}}
                                </td>

                                <td>
                                <button class="btn btn-warning mb-2" type="button" data-toggle="collapse" data-target="#collapse{{ $task->id }}" aria-expanded="false" aria-controls="collapse{{ $task->id }}">
                                        Timer
                                </button>
                                <a class="btn btn-info" type="button" href="/tasks/{{ $task->id }}/view">
                                        View
                                </a>
                                </td>

                    @endforeach
                  </tr>

                @foreach($tasks as $task)
                <tr>
                    <div class="collapse pr-3 col" id="collapse{{ $task->id }}">
                        <div class="card card-body">
                            <h1>{{ ucwords($task->title) }}</h1>
                            <p id="start{{ $task->id }}"> Start time: 00:00 </p>
                            <p id="stop{{ $task->id }}"> Stop time: 00:00 </p>
                            <p id="sessionTime{{ $task->id }}">Session time:  00:00</p>
                            <!-- <p id="monthAvg{{ $task->id }}">Month Avg: 00:00</p>
                            <p id="weekAvg{{ $task->id }}">Week Avg: 00:00</p>
                            <p id="yearAvg{{ $task->id }}">Year Avg: 00:00</p> -->
                            <button id="start" onclick="startTimer({{ $task->id }})" class="btn btn-success mb-2" >Start Timer</button>
                            <button id="stop" onclick="stopTimer({{ $task->id }})" class="btn btn-danger">Stop Timer</button>
                        </div>
                    </div>
                </tr>
                @endforeach
                </tbody>
              </table>
        </div>

        <a href="tasks/add" class="btn btn-primary mt-3"> Add Task </a>
    </div>
@endsection

@section('scripts')
    <script>
        function startTimer(id){
            $.ajax({
                url: "/tasks/startTimer",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                data: {id: id},
                cache: false,
                async: false,
                dataType: "json",
                processData: "false"
            })

            .done(function(response){
                $('#start' + id).html("Start time: " + response.start);
            })
            .fail(function(response){

            });
        }

        function stopTimer(id){
            $.ajax({
                url: "/tasks/stopTimer",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                data: {id: id},
                cache: false,
                async: false,
                dataType: "json",
                processData: "false"
            })

            .done(function(response){
                $('#stop' + id).html("Stop time: " + response.stop);
                $('#sessionTime' + id).html("Session time: " + response.totalTime);
		        $('.alert').html("Total time worked today: " + response.allTime);
                // $('#monthAvg' + id).html("Month average: " + response.monthAvg);
                // $('#weekAvg' + id).html("Week average: " + response.weekAvg);
                // $('#yearAvg' + id).html("Year average: " + response.yearAvg);
            })
            .fail(function(response){

            });
        }
    </script>
@endsection

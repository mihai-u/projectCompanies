<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Company;
use App\Project;
use App\User;
use Illuminate\Support\Carbon;
use App\Events\TaskStarted;
use App\WorkingHour;

class TaskController extends Controller
{
    //This function will display the tasks blade
    public function show(){
	$users = User::select('id', 'username', 'name')->get();
        $tasks = Task::select('worked_time', 'id', 'title', 'description', 'created_at', 'owner_id', 'responsible_id', 'status', 'company_id', 'project_id')->where('responsible_id', Auth::user()->id)->orWhere('owner_id', Auth::user()->id)->get();
        $companies = Company::select('id', 'companyName')->get();
        $projects = Project::select('id', 'title')->get();
        $now = Carbon::now();

        return view('tasks.tasks', compact('tasks', 'users', 'companies', 'projects', 'now'));
    }

    //This function will display the task add blade
    public function getAdd(){
        $companies = Company::all()->where('user_id', Auth::user()->id);
        $projects = Project::all();
        $users = User::all();

        return view('tasks.add', compact('companies', 'users'))->with('projects', $projects);
    }

    //This function is called when a new task creation is required
    //It return an error message
    public function postAdd(Request $request){

        $task = new Task();

        if($request->title != null){
            $task->title = $request->title;
        }else{
            return response()->json([
                'errorMessage' => 'titleEmpty'
            ]);
        }
        if($request->description != null){
            $task->description = $request->description;
        }else{
            return response()->json([
                'errorMessage' => 'descriptionEmpty'
            ]);
        }
        if($request->responsible != null){
            $task->responsible_id = $request->responsible;
        }else{
            return response()->json([
                'errorMessage' => 'responsibleEmpty'
            ]);
        }
        if($request->company != null){
            $task->company_id = $request->company;
        }else{
            return response()->json([
                'errorMessage' => 'companyEmpty'
            ]);
        }
        if($request->project != null){
            $task->project_id = $request->project;
        }else{
            return response()->json([
                'errorMessage' => 'projectEmpty'
            ]);
        }

        $task->owner_id = Auth::user()->id;
        $task->save();

        return response()->json([
            'errorMessage' => ''
        ]);

    }

    //This function will return projects of the requested company
    public function fetch(Request $request){

        $project = Project::where('company_id', $request->id)
                            ->get();

        return response()->json([
            'project' => $project
        ]);
    }

    //This function will display the edit tasks edit page for a specific task
    public function getEdit($id){
        $task = Task::findOrFail($id);
        $users = User::select('id', 'username', 'name')->where('type', 'user')->get();

        return view('tasks.edit', ['task' => $task], compact('users'));
    }

    //This function is called when an info update is requested for a specific task
    //It return the new title and description saved
    public function updateInfo(Request $request){
        $task = Task::find($request->id);
        if($request->title != null){
            $task->title = $request->title;
        }

        if($request->description != null){
            $task->description = $request->description;
        }

        if($request->status != null){
            $task->status = $request->status;
        }
        $task->save();


        return response()->json([
            'newTitle' => $task->title,
            'newDescription' => $task->description
        ]);
    }

    //This function is called when the /view page is requested for tasks
    public function viewTask($id){
        $task = Task::findOrFail($id);
        $users = User::all();

        return view('tasks.view', ['task' => $task], compact('users'));
    }

    //This function is called when a task deletion is requested (soft delete)
    public function deleteTask($id){
        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json([

        ]);
    }

    //This function is used in /tasks to start the timer
    public function startTimer(Request $request){

        $task = Task::findOrFail($request->id); //gets the task or fails
        $start = Carbon::now()->toDateTimeString(); //gets the curent date

        $workingHour = new WorkingHour(); //creates a new working hour
        $workingHour->task_id = $task->id;
        $workingHour->start = $start;
        $workingHour->user_id = Auth::user()->id;

        $workingHour->save();

        $parsedStart = Carbon::parse($workingHour->start); //parses the start date

        return response()->json([
            'start' => $parsedStart->format('H:i')
        ]);

    }

    //This function is called when the 'stop button' is clicked
    //It saves the month, week and year average and total time worked in the session
    public function stopTimer(Request $request){

        $task = Task::findOrFail($request->id);
        $workingHour = WorkingHour::where('task_id', $task->id)->orderBy('id', 'desc')->first();

        if($workingHour->stop == null){
            $workingHour->stop = Carbon::now()->toDateTimeString();
            $workingHour->save();
        }else{
            $id = $workingHour->id - 1;
            $workingHour = WorkingHour::where('id', $id)->first();
            $workingHour->stop = Carbon::now()->toDateTimeString();
            $workingHour->save();
        }

        $start = Carbon::parse($workingHour->start)->second;
        $stop = Carbon::parse($workingHour->stop)->second;

        // dd($start, $stop);

        $totalTime = $stop - $start;

        // $totalTime = gmdate('H:i:s', $stop->diffInSeconds($start));
        $workingHour->totalTime = $totalTime;
        $workingHour->save();

        $task->worked_time = $workingHour->sum('totalTime');
        $task->save();


        // month average
        $whCurrentMonth = WorkingHour::where('created_at', '>=', Carbon::now()->startOfMonth())->get();

        $whFirstInstance = Carbon::parse($whCurrentMonth->first()->totalTime);
        $whLastInstance = Carbon::parse($whCurrentMonth->reverse()->first()->totalTime);

        $whMonthAvg = $whFirstInstance->average($whLastInstance)->format('H:i:s');

        //week avg
        $whCurrentWeek = WorkingHour::where('created_at', '>=', Carbon::now()->startOfWeek())->get();

        $whFirstWeekInstance = Carbon::parse($whCurrentWeek->first()->totalTime);
        $whLastWeekInstance = Carbon::parse($whCurrentWeek->reverse()->first()->totalTime);

        $whWeekAvg = $whFirstWeekInstance->average($whLastWeekInstance)->format('H:i:s');

        //year avg
        $whCurrentYear = WorkingHour::where('created_at', '>=', Carbon::now()->startOfYear())->get();

        $whFirstYearInstance = Carbon::parse($whCurrentYear->first()->totalTime);
        $whLastYearInstance = Carbon::parse($whCurrentYear->reverse()->first()->totalTime);

        $whYearAvg = $whFirstYearInstance->average($whLastYearInstance)->format('H:i:s');

        $parsedStop = Carbon::parse($workingHour->stop);
        
        // dd($task->worked_time);

        return response()->json([
            'stop' => $parsedStop->format('H:i'),
            'totalTime' => gmdate('H:i:s', $workingHour->totalTime),
	        'allTime' => gmdate('H:i:s', $task->worked_time),
            'monthAvg' => $whMonthAvg,
            'weekAvg' => $whWeekAvg,
            'yearAvg' => $whYearAvg
        ]);

    }

}

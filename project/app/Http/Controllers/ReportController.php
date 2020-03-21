<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\WorkingHour;
use App\User;
use Illuminate\Support\Carbon;
use App\Company;
use App\Project;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //This function displays the '/reports' webpage
    public function show(){
        // $companies = Company::select('id', 'companyName')->get();
        $companies = Company::select('id', 'companyName')
                    ->where('user_id', Auth::user()->id)
                    ->with(['project' => function($project){
                        $project->select('id', 'title', 'company_id')
                              ->with(['tasks' => function($task){
                                    $task->select(['id', 'title', 'project_id', 'responsible_id', 'worked_time']);
                                }]);
                    }])->get();
                    
        return view('report.show', compact('companies'));
    }

    //This function calculates the time worked per task by ALL employees
    //It returns a json response with the workingHours and companies
    public function calculateTimeByEmployee(Request $request){

        //Creates a query containing all working hours and the total time worked per task
        $workingHours = WorkingHour::select('user_id', 'task_id', DB::raw('sum(totalTime) worked'))->groupBy('task_id')->whereBetween('start', [$request->from, $request->to])->get();

        //Creates a query containing all companies, projects and tasks
        $companies = Company::where('id', $request->id)
                    ->select('id', 'companyName')
                    ->with(['project' => function($project){
                        $project->select('id', 'title', 'company_id')
                              ->with(['tasks' => function($task){
                                    $task->select(['id', 'title', 'project_id', 'responsible_id']);
                                }]);
                    }])->get();

        return response()->json([
            'workingHours' => $workingHours,
            'companies' => $companies
        ]);
    }

    //This function calculates the time worked on a specific task by employees
    public function employeeReport(Request $request){

        //Creates a query containing all working hours with the total time summed by employee
        $workingHours = WorkingHour::where('task_id', $request->id)->select('user_id', DB::raw('sum(totalTime) worked'))->groupBy('user_id')->whereBetween('start', [$request->from, $request->to])->get();

        //Creates a query contining all employees with only the id and username
        $employees = User::select('id', 'username', 'name')->get();

        return response()->json([
            'workingHours' => $workingHours,
            'employees' =>$employees
        ]);
    }
}

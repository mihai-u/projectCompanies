<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Project;;
use App\Task;


//Defined, but not used. It was replaced with dashboard

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
            $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(){
        if(Auth::check()){
            $nrCompanies = Company::with('user')->where('user_id', Auth::user()->id)->count();
            $nrProjects = Project::with('companies')->where('user_id', Auth::user()->id)->count();
            $nrTasks = Task::where('responsible_id', Auth::user()->id)->orWhere('owner_id', Auth::user()->id)->count();

    
            return view('dashboard', compact('nrCompanies', 'nrProjects', 'nrTasks'));
        }else{
            return redirect('/login');  
        }
    }
}

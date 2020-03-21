<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Company;
use Psy\Command\WhereamiCommand;

class ProjectsController extends Controller
{
    // //This function will store a new project based on the user input
    // //It returns the companies blade with compacted companies based on the user type
    // public function store(Request $request){
    //     $project = new Project();
    //     dd($request->photo);
    //     if($request->photo != null){
    //         $project->photo = $request->photo;
    //     }else{
    //         $project->photo = 'https://i.ibb.co/jgjZXd7/undraw-Organizing-projects-0p9a.png';
    //     }
        
    //     $project->title = $request->projectTitle;
    //     $project->description = $request->projectDescription;
    //     $project->user_id=Auth::id();
    //     $project->company_id=$request->exampleFormControlSelect1;

    //     $project->save();

    //     if(Auth::user()->isAdmin == 1){
    //         $companies = Company::with('project')->with('user')->get();
    //     }else{
    //         $companies = Company::with('project')->with('user')->where('user_id',Auth::user()->id)->get();
    //     }

    //     return view('companies', compact('companies'));
    // }

    //This function is used to display the edit page specific to the requested project
    public function edit($id, $pr_id){
        $project = Project::findOrFail($pr_id);
        $company = Company::findOrFail($id);
        $companies = Company::with('project')->with('user')->where('user_id', Auth::user()->id)->get();

        // dd($project);
        return view('edit', ['company' => $company, 'project' => $project], compact($project));
    }

    //This function will change the project photo url based on the $request
    //It return a json with the new photo url
    public function updatePhoto(Request $request){

        $project = Project::find($request->id);
            if($request->photo != null){        
                $project->photo = $request->photo;
                $project->save();
            }

        return response()->json([
            'newPhotoUrl' => $project->photo
        ]);
    }

    //This function is called when an info update is requested
    //It returns a json with the new title and description
    public function updateInfo(Request $request){
        $project = Project::find($request->id);
        if($request->title != null){
            $project->title = $request->title;
        }

        if($request->description != null){
            $project->description = $request->description;
        }
        $project->save();


        return response()->json([
            'newTitle' => $project->title,
            'newDescription' => $project->description
        ]);
    }

    //This function is called when the add page for the projects is requested
    //It return the projects.add blade
    public function getAdd(){
        return view('projects.add');
    }

    //This function is called when a new project is created
    //It return a json with an errorMessage
    public function postAdd($id, Request $request){
        $project = new Project();
        $company = Company::findOrFail($id);

        if($request->title != null){
            $project->title = $request->title;
        }else{
            return response()->json([
                'errorMessage' => 'titleEmpty'
            ]);
        }

        if($request->description != null){
            $project->description = $request->description;
        }else{
            return response()->json([
                'errorMessage' => 'descriptionEmpty'
            ]);
        }
        
        if($request->photo != null){
            $project->photo = $request->photo;
        }else{
            $project->photo = 'https://i.ibb.co/jgjZXd7/undraw-Organizing-projects-0p9a.png';
        }
        $project->company_id = $company->id;
        $project->user_id = Auth::user()->id;
        $project->save();

        return response()->json([
            'errorMessage' => ''
        ]);
    }

    //This function when a project delete is requested
    //It returns an empty response json
    public function deleteProject($company_id, $id){
        $project = Project::findOrFail($id);

        $project->delete();

        return response()->json([

        ]);
    }
}


// Must return the project with the respective id

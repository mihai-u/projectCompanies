<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Project;
use App\Task;

class PagesController extends Controller
{

    //This function return the welcome blade
    public function home(){
        if(Auth::check()){
            return view('dashboard');
        }else{
            return redirect('/login');
        }
    }

    //This function return the dashboard blade with the number of companies, projects and tasks corresponding to the authenticated user
    public function dashboard(){
        if(Auth::check()){
            $nrCompanies = Company::with('user')->where('user_id', Auth::user()->id)->count();
            $nrProjects = Project::with('companies')->where('user_id', Auth::user()->id)->count();
            $nrTasks = Task::where('responsible_id', Auth::user()->id)->orWhere('owner_id', Auth::user()->id)->count();

            return view('dashboard', compact('nrCompanies', 'nrProjects', 'nrTasks'));
        }else{
            return redirect('/login');
        }
    }

    //This function return the admin page
    public function admin(Request $request){
        $users = User::select('username', 'id', 'name', 'email', 'type')->get();
        return view('admin.adminPanel', compact('users'));
    }

    //This function is used in the adminPanel blade to update the user username
    //It returns a json with the new username
    public function updateUsername(Request $request){
        $user = User::find($request->userid);

        $user->username = $request->name;
        $user->save();

        return response()->json([
            'username' => $user->username
        ]);
    }

    //This function is used in the adminPanel blade to update the user email
    //It return a json with the new email or an error message if the email is invalid
    public function updateEmail(Request $request){
        $user = User::find($request->userid);

        $validator = Validator::make($request->all(), [
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users'
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=> "Invalid Email Format"
            ]);
        }

        $user->email = $request->userEmail;
        $user->save();

        return response()->json([
            'userEmail' => $user->email
        ]);
    }

    //This function is used in the adminPanel blade to update the user admin status
    //It return a json with the new admin value (0 for non admin, 1 for admin)
    public function updateAdmin(Request $request){
        $user = User::find($request->userid);

        $user->isAdmin = $request->adminValue;
        $user->save();

        return response()->json([
            'adminValue' => $user ->isAdmin
        ]);
    }

    //This function is used in the adminPanel blade to update the user password
    //It returns a json with the new password
    public function updatePassword(Request $request){
        $user = User::find($request->userid); //finds the user based on the id from $request
        $hashedPassword = Hash::make($request->newPassword); //Hashes the new password that the user has provided
        $user->password = $hashedPassword;
        $user->save(); //Saves the new hashed password

        return response()->json([
            'newPassword' => $user->password
        ]);
    }
}

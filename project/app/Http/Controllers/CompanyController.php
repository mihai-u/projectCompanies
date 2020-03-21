<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;

class CompanyController extends Controller
{
    //The following function returns the /companies blade
    public function index(){
        //This will return only the companies the authenticated user has created
        $companies = Company::with('project')->with('user')->where('user_id',Auth::user()->id)->get();

        return view('companies', compact('companies'));
    }

    //This function will show the projects based on the company id from the url
    public function projects($id){
        $company = Company::findOrFail($id);
        $companies = Company::with('project')->with('user')->where('user_id', Auth::user()->id)->get();

        return view('projects', ['company' => $company], compact('companies'));
    }

    //This function is called to display the edit page of the company
    public function edit($id){
        $company = Company::findOrFail($id);

        return view('company.edit', ['company' => $company]);
    }

    //This function is called when the user insters a new photo url for the company
    public function updatePhoto(Request $request){

        $company = Company::find($request->id);
        if($request->photo != null){
            $company->photo = $request->photo;
            $company->save();
        }

        return response()->json([
            'newPhotoUrl' => $company->photo
        ]);
    }

    //This function is called when the user changes the company name and description
    public function updateInfo(Request $request){
        $company = Company::find($request->id);

        if($request->companyName != null){
            $company->companyName = $request->companyName;
        }

        if($request->companyDescription != null){
            $company->companyDescription = $request->companyDescription;
        }
        
        $company->save();


        return response()->json([
            'newName' => $company->companyName,
            'newDescription' => $company->companyDescription
        ]);

    }

    //This function will display the company add page
    public function getAdd(){
        return view('company.add');
    }


    //The following function will be called when a new company is created and will save it to the database
    public function postAdd(Request $request){
        $company = new Company();
        if($request->companyName == null){
            return response()->json([
                'errorMessage' => 'Company name cannot be null'
            ]);
        }

        if($request->companyDescription == null){
            return response()->json([
                'errorMessage' => 'Company description cannot be null'
            ]);
        }

        if($request->photo == null){
            $company->photo = "https://i.ibb.co/k8Dr9sW/undraw-business-shop-qw5t-1.png";
        }else{
            $company->photo = $request->photo;
        }
        $company->companyName = $request->companyName;
        $company->companyDescription = $request->companyDescription;
        
        $company->user_id = Auth::user()->id;
        $company->save();

        return response()->json([
            'errorMessage' => ''
        ]);
    }

    //The following function will be called when the user deletes a company (soft delete)
    public function deleteCompany($id){
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json([

        ]);
    }

}

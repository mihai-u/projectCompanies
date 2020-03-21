<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', 'PagesController@dashboard')->middleware(['auth']);
Route::get('/complete-registration', 'Auth\RegisterController@completeRegistration');
Route::get('/', 'HomeController@index')->middleware(['auth']);

Route::middleware(['auth', 'App\Http\Middleware\AdminMiddleware'])->group(function () {
    Route::get('/admin/adminPanel', 'PagesController@admin');
    // Admin Routes
    Route::post('/admin/adminPanel/updateUsername', 'PagesController@updateUsername');
    Route::post('/admin/adminPanel/updateEmail', 'PagesController@updateEmail');
    Route::post('/admin/adminPanel/updateAdmin', 'PagesController@updateAdmin');
    Route::post('/admin/adminPanel/updatePassword', 'PagesController@updatePassword');
    // End of Admin Routes
});

Route::middleware(['auth','App\Http\Middleware\UserMiddleware'])->group(function () {
    Route::get('/dashboard', 'PagesController@dashboard');
    Route::get('/companies', 'CompanyController@index');
    Route::get('/companies/{company}/projects', 'CompanyController@projects');
    Route::get('/companies/{company}/delete', 'CompanyController@delete');
    // Route::post('/home', 'CompanyController@store');

    // Company Routes
    Route::get('/companies/add', 'CompanyController@getAdd');
    Route::post('/companies/add', 'CompanyController@postAdd');
    Route::get('/companies/{company}/edit', 'CompanyController@edit');
    Route::post('/companies/{company}/edit/updatePhoto', 'CompanyController@updatePhoto');
    Route::post('/companies/{company}/edit/updateInfo', 'CompanyController@updateInfo');
    Route::delete('/companies/{company}/delete', 'CompanyController@deleteCompany');
    // End of Company Routes

    // Project Routes

        //Create Route
        Route::get('/companies/{company}/projects/add', 'ProjectsController@getAdd');
        Route::post('/companies/{company}/projects/add', 'ProjectsController@postAdd');

        //Update Routes
        Route::get('/companies/{company}/projects/{project}/edit', 'ProjectsController@edit');
        Route::post('/companies/{company}/projects/{project}/edit/updatePhoto', 'ProjectsController@updatePhoto');
        Route::post('/companies/{company}/projects/{project}/edit/updateInfo', 'ProjectsController@updateInfo');
        Route::post('/companies/{company}/projects/{project}/edit/updateInfo', 'ProjectsController@updateInfo');

        //Delete Route
        Route::delete('/companies/{company}/projects/{project}/delete', 'ProjectsController@deleteProject');
        // End of Project Routes

    //Profile Routes
    Route::get('/user/profile', 'ProfileController@profile');
    Route::post('/user/profile/updateInfo', 'ProfileController@updateInfo');
    Route::post('/user/profile/updatePhoto', 'ProfileController@updatePhoto');
    Route::post('/user/profile/disableGoogleAuth', 'ProfileController@disableTwoFactor');
    Route::post('/user/profile/enableGoogleAuth', 'ProfileController@enableTwoFactor');
    //End of Profile Routes

    // Tasks Routes
        //Adding Routes
        Route::get('/tasks', 'TaskController@show');
        Route::get('/tasks/add', 'TaskController@getAdd');
        Route::post('/tasks/add', 'TaskController@postAdd');
        Route::post('/tasks/add/fetch', 'TaskController@fetch');

        //Editing Routes
        Route::get('/tasks/{task}/edit', 'TaskController@getEdit');
        Route::post('/tasks/{task}/edit/updateInfo', 'TaskController@updateInfo');

        //View task
        Route::get('/tasks/{task}/view', 'TaskController@viewTask');

        //Delete Task
        Route::delete('/tasks/{task}/delete', 'TaskController@deleteTask');

        //Update Time
        Route::post('/tasks/startTimer', 'TaskController@startTimer');
        Route::post('/tasks/stopTimer', 'TaskController@stopTimer');
    // End of Tasks Routes

    //Report routes
    Route::get('/reports', 'ReportController@show');
    Route::post('/reports/employeeTime', 'ReportController@calculateTimeByEmployee');
    Route::post('/reports/employeeReport', 'ReportController@employeeReport');
});


Auth::routes(['verify' => true]);
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

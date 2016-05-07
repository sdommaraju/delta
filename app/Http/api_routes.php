<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->post('access_token', function() {
        return Response::json(Authorizer::issueAccessToken());
    });
    
});


$api->version('v1',['prefix' => 'api','namespace' => 'App\Http\Controllers\Api'],function ($api) {
    
    $api->group(['middleware' => 'oauth-client'],function($api){
        $api->get('users', 'UserController@index');
    });
    
   
    $api->group(['middleware' => 'oauth','oauth-user'],function($api){
        
        $api->resource('users', 'UserController');
       
        $api->get('user/profile', 'UserController@profile');
        
        $api->get('user/roles', 'UserController@roles');
        
        
        $api->get('candidate/search','CandidateController@search');
        
        $api->resource('candidate','CandidateController');
        
        $api->resource('agency','AgencyController');
        
        $api->resource('companies','CompanyController');
        
        $api->resource('jobs','JobsController');
        
        $api->get('jobs/{id}/candidates','JobsController@getCandidates');
        
        $api->post('candidate/{id}/uploadResume','CandidateController@uploadResume');
        $api->post('candidate/{id}/uploadProfile','CandidateController@uploadProfile');
        $api->post('candidate/{id}/skills','CandidateController@addSkill');
        $api->get('candidate/{id}/skills','CandidateController@getSkills');
        
        $api->get('candidate/{id}/jobs','CandidateJobController@getJobs');
        $api->post('candidate/{candidate_id}/jobs/{job_id}','CandidateJobController@assignJob');
        $api->post('candidate/{candidate_id}/jobs/{job_id}/change-stage','CandidateJobController@changeStage');
        
        //$api->delete('candidate/{id}/skills/{id}','CandidateController@deleteSkill');
        
//         $api->resource('projects', 'App\http\Controllers\Api\ProjectsController');
//         $api->get('users/{id}/projects', 'App\http\Controllers\Api\UserController@projects');
//         $api->resource('gallery', 'App\http\Controllers\Api\UserGalleryController');
//         $api->get('gallery/{id}/download', 'App\http\Controllers\Api\UserGalleryController@download');
        
//        $api->resource('timesheet', 'App\http\Controllers\Api\UserTimesheetController');
    });
        
});
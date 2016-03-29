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


$api->version('v1',function ($api) {
    
    $api->group(['middleware' => 'oauth-client'],function($api){
        $api->get('users', 'App\http\Controllers\Api\UserController@index');
    });
    
   
    $api->group(['middleware' => 'oauth','oauth-user'],function($api){
        
        $api->resource('users', 'App\http\Controllers\Api\UserController');
       
        $api->get('user/profile', 'App\http\Controllers\Api\UserController@profile');
        
        $api->resource('candidate','App\http\Controllers\Api\CandidateController');
//         $api->resource('projects', 'App\http\Controllers\Api\ProjectsController');
//         $api->get('users/{id}/projects', 'App\http\Controllers\Api\UserController@projects');
//         $api->resource('gallery', 'App\http\Controllers\Api\UserGalleryController');
//         $api->get('gallery/{id}/download', 'App\http\Controllers\Api\UserGalleryController@download');
        
//        $api->resource('timesheet', 'App\http\Controllers\Api\UserTimesheetController');
    });
        
});
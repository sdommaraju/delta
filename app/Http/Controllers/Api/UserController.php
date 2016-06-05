<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Models\Role;
use App\Http\Models\Groups;
use App\Http\Models\Projects;
use App\Http\Requests\UsersRequest;
use App\Http\Controllers\Api\BaseController;
use App\Http\Transformers\UserTransformer;
use App\Http\Transformers\UserProjectsTransformer;
use App\Http\Transformers\ProjectsTransformer;
use App\Http\Transformers\RolesTransformer;
use App\Http\Transformers\GroupsTransformer;
use Dingo\Api\Auth\Auth;
use LucaDegasperi\OAuth2Server\Authorizer;

class UserController extends BaseController
{
   public function __construct(Authorizer $authorizer){
        
        //$this->middleware('oauth-client',["only"=>["index"]]);
        $this->middleware('oauth-user',["except"=>["index"]]);
        $this->authorizer = $authorizer;
    }
    /**
     * @api {get} /users Fetch All Users.
     * @apiVersion 1.0.0
     * @apiName GetUsers
     * @apiGroup Users
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "firstname": "John",
     *       "lastname": "Doe"
     *     }
     *
     * @apiError UsersNotFound No Users found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UsersNotFound"
     *     }
     */
    public function index()
    {
        $user_id=$this->authorizer->getResourceOwnerId();
        $user = User::findorfail($user_id);

        $users = User::where('agency_id','=',$user->agency_id)->get();
        return $this->response->collection($users, new UserTransformer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @api {post} /users Create New User.
     * @apiVersion 1.0.0
     * @apiName CreateUser
     * @apiGroup Users
     * 
     * @apiParam {String} name User Name.
     * @apiParam {String} email User Email.
     * @apiParam {String} password User Password.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "firstname": "John",
     *       "lastname": "Doe",
     *       "updated_at": "2016-03-11 11:10:57",
     *       "created_at": "2016-03-11 11:10:57",
     *       "id": 6
     *     }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "Can't Create User"
     *     }
     */
    public function store(UsersRequest $request)
    {
        $user_id=$this->authorizer->getResourceOwnerId();
        $userData = User::findorfail($user_id);
        
        $user = $request->input();
        $user['password'] = bcrypt($user['password']);
        
        $user['agency_id'] = $userData->agency_id;
        //Handle Group name and id
        if($user['group_type']=='add'){
            $group = Groups::create(array('name'=>$user['group'],'agency_id'=>$userData->agency_id));
            $user['group_id'] = $group->id;
        }
        if($user['group_type']=='select'){
            $user['group_id'] = $user['group'];
        }
        return $this->response->item(User::create($user),new UserTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @api {delete} /users Delete Existing User.
     * @apiVersion 1.0.0
     * @apiName DeleteUser
     * @apiGroup Users
     * 
     * @apiParam {Integer} id User Id.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "firstname": "John",
     *       "lastname": "Doe",
     *       "updated_at": "2016-03-11 11:10:57",
     *       "created_at": "2016-03-11 11:10:57",
     *       "id": 6
     *     }
     *
     * @apiError NoUserFound.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "No User Found with ID {id}"
     *     }
     */
    public function destroy($id)
    {
        if($user = User::findorfail($id)){
            if(User::destroy($id)){
                return $user;
            }
        }
    }
    
    /**
     * @api {get} /users/{id}/projects Get all projects for given user
     * @apiVersion 1.0.0
     * @apiName getUserProjects
     * @apiGroup User Projects
     *
     * @apiParam {Integer} id User Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": [
     *           {
     *             "id": 2,
     *             "project_name": "SuperGas",
     *             "client": "SuperGas",
     *             "user_id": 2
     *           },
     *           {
     *             "id": 3,
     *             "project_name": "Democrasoft",
     *             "client": "Democrasoft",
     *             "user_id": 2
     *           }
     *         ]
     *     }
     *
     * @apiError No User Found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "No User Found with ID {id}"
     *     }
     */
    public function projects($id){
        $user = User::findorfail($id);
        $projects = $user->projects;

        return $this->response->collection($projects, new ProjectsTransformer);
    }
    public function profile(Authorizer $authorizer){
        $user_id=$authorizer->getResourceOwnerId();
        $user = User::findorfail($user_id);
        return $this->response->item($user, new UserTransformer);
    }
    
    /**
     * @api {get} /user/roles Fetch All User Roles.
     * @apiVersion 1.0.0
     * @apiName GetRoles
     * @apiGroup Users
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "firstname": "John",
     *       "lastname": "Doe"
     *     }
     *
     * @apiError UsersNotFound No Users found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UsersNotFound"
     *     }
     */
    public function roles()
    {
        $roles = Role::where('id','>','2')->get();
        return $this->response->collection($roles, new RolesTransformer);
    }
    public function groups()
    {
        $user_id=$this->authorizer->getResourceOwnerId();
        $user = User::findorfail($user_id);
        
        $groups = Groups::where('agency_id','=',$user->agency_id)->get();

        return $this->response->collection($groups, new GroupsTransformer);
    }
}

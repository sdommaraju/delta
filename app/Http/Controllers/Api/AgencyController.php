<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Models\Agency;
use App\Http\Models\User;
use App\Http\Requests\AgencyRequest;
use App\Http\Requests\AgencyUserRequest;
use App\Http\Transformers\AgencyTransformer;
use App\Http\Requests\AgencyAccountActivationRequest;
use App\Http\Models\Role;
use Mail;

class AgencyController extends BaseController
{
    /**
     * @api {get} /agency Fetch All Agencies.
     * @apiVersion 1.0.0
     * @apiName Getagencies
     * @apiGroup Agencies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": [
     *       {
     *         "id": 1,
     *         "name": "Srinu",
     *         "logo_url": "srinu.jpg",
     *         "email": "sri@sri.com",
     *         "address": "dfsdf",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "phone_number": "9949290090",
     *         "zip": "sdf",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         },
     *         "user_id": null
     *       }
     *     ]
     *   }
     *
     * @apiError AgenciesNotFound No Agencies found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "AgenciesNotFound"
     *     }
     */
    public function index()
    {
        $agencies = Agency::all();
        return $this->response->collection($agencies, new AgencyTransformer);
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
     * @api {post} /agency Create New Agency and Agency Admin.
     * @apiVersion 1.0.0
     * @apiName CreateAgency
     * @apiGroup Agencies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {String} email Agency Admin Email.
     * @apiParam {String} password Agency Admin Password
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *       "id": 5,
     *       "name": null,
     *       "logo_url": null,
     *       "email": "srinivasulumsc2@gmail.com",
     *       "address": null,
     *       "city": null,
     *       "state": null,
     *       "phone_number": null,
     *       "zip": null,
     *       "created_at": {
     *         "date": "2016-04-04 09:42:46.000000",
     *         "timezone_type": 3,
     *         "timezone": "UTC"
     *       },
     *       "user_id": 7
     *     }
     *   }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 500 Server Error
     *     {
     *       "error": "Can't Create Agency"
     *     }
     */
    public function store(AgencyUserRequest $request)
    {
        $userData = $request->input();
       
        //Create USer with Email and Password
        //$userData['password'] = bcrypt($userData['password']);
        $userData['password'] = uniqid();
        $userData['role_id'] = 2;
        
        $user = User::create($userData);
        
        //Create Agency with Email , later update all agency information through Update Method
        $agency = Agency::create($userData);
        
        $user->agency_id = $agency->id;
        $user->save();
        $agency->user_id = $user->id;
        
        $mail_data['first_name'] = $user->first_name;
        $mail_data['last_name'] = $user->last_name;
        $mail_data['agency_name'] = $agency->name;
        
        $account_activation_link = config('app.url').'/account/activation?token='.base64_encode($user->id.'-'.$user->email.'-'.$userData['password']);
        
        
        $mail_data['activation_link'] = $account_activation_link;
        
        
        Mail::send('agency.welcome', $mail_data, function($message) use ($user, $agency) {
            $message->to($user->email)->subject('Delta :: '.$agency->name.' :: Account Activation');
        });
        
        return $this->response->item($agency,new AgencyTransformer);
    }

    /**
     * @api {get} /agency/:id Fetch Agency Details by Id.
     * @apiVersion 1.0.0
     * @apiName GetAgency
     * @apiGroup Agencies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Agency Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *       "id": 5,
     *       "name": null,
     *       "logo_url": null,
     *       "email": "srinivasulumsc2@gmail.com",
     *       "address": null,
     *       "city": null,
     *       "state": null,
     *       "phone_number": null,
     *       "zip": null,
     *       "created_at": {
     *         "date": "2016-04-04 09:42:46.000000",
     *         "timezone_type": 3,
     *         "timezone": "UTC"
     *       },
     *       "user_id": 7
     *     }
     *   }
     *
     * @apiError AgencyNotFound No Agency found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "AgencyNotFound"
     *     }
     */
    public function show($id)
    {
       $agency = Agency::findorfail($id);
       return $this->response->item($agency,new AgencyTransformer);
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
     * @api {put} /agency/:id Update Agency Details by Id.
     * @apiVersion 1.0.0
     * @apiName PutAgency
     * @apiGroup Agencies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Agency Id.
     * @apiParam {String} name Agency Name.
     * @apiParam {String} logo_url Agency Logo URL.
     * @apiParam {String} address Agency Address.
     * @apiParam {String} city Agency City.
     * @apiParam {String} state Agency State.
     * @apiParam {String} zip Agency ZipCode.
     * @apiParam {String} phone_number Agency Phone Number.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *       "id": 5,
     *       "name": null,
     *       "logo_url": null,
     *       "email": "srinivasulumsc2@gmail.com",
     *       "address": null,
     *       "city": null,
     *       "state": null,
     *       "phone_number": null,
     *       "zip": null,
     *       "created_at": {
     *         "date": "2016-04-04 09:42:46.000000",
     *         "timezone_type": 3,
     *         "timezone": "UTC"
     *       },
     *       "user_id": 7
     *     }
     *   }
     *
     * @apiError AgencyNotFound No Agency found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "AgencyNotFound"
     *     }
     */
    public function update(AgencyRequest $request, $id)
    {
        $agency = Agency::findorfail($id);
        
        $agencyData = $request->input();
        $agency->name = $agencyData['name'];
        //$agency->logo_url = $agencyData['logo_url'];
        $agency->address = $agencyData['address'];
        $agency->city = $agencyData['city'];
        $agency->state = $agencyData['state'];
        $agency->zip = $agencyData['zip'];
        $agency->phone_number = $agencyData['phone_number'];
       
        $agency->save();
        
        return $this->response->item($agency,new AgencyTransformer);
    }

    /**
     * @api {delete} /agency/:id Delete Agency by Id
     * @apiVersion 1.0.0
     * @apiName DeleteAgency
     * @apiGroup Agencies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Agency Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *       "id": 5,
     *       "name": null,
     *       "logo_url": null,
     *       "email": "srinivasulumsc2@gmail.com",
     *       "address": null,
     *       "city": null,
     *       "state": null,
     *       "phone_number": null,
     *       "zip": null,
     *       "created_at": {
     *         "date": "2016-04-04 09:42:46.000000",
     *         "timezone_type": 3,
     *         "timezone": "UTC"
     *       },
     *       "user_id": 7
     *     }
     *   }
     *
     * @apiError AgencyNotFound No Agency found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "AgencyNotFound"
     *     }
     */
    public function destroy($id)
    {
        $agency = Agency::findorfail($id);
        $agency->delete();
        return $this->response->item($agency,new AgencyTransformer);
    }
    public function activation(Request $request){
        $tokenData = $request->input();
        $userTokenString = $tokenData['token'];
        if($userTokenString!=""){
            $userToken = explode('-',base64_decode($userTokenString));
            
            $userId = trim($userToken[0]);
            $email = trim($userToken[1]);
            $uid = trim($userToken[2]);
            
            if($userId!="" && $email!="" && $uid!=""){
                $userRecord = User::find($userId);
                $userRecord->access_token = $userTokenString;
                if($userRecord && $userRecord->email==$email && $userRecord->password==$uid){
                    return view('agency.activation',$userRecord);
                } else {
                    return view('agency.activation',array('message'=>'Invalid Activation Link and Account already activated.'));
                }
            } else {
                return view('agency.activation',array('message'=>'Invalid Activation Link and Account already activated.'));
            }
        } else {
            return view('agency.activation',array('message'=>'Invalid Activation Link and Account already activated.'));
        }
    }
    public function updatePassword(AgencyAccountActivationRequest $request){
     
        $formData = $request->input();

        $password = $formData['password'];
        $userTokenString = $formData['access_token'];
        
        if($userTokenString!=""){
            $userToken = explode('-',base64_decode($userTokenString));
            
            $userId = trim($userToken[0]);
            $email = trim($userToken[1]);
            $uid = trim($userToken[2]);
        
            if($userId!="" && $email!="" && $uid!=""){
                $userRecord = User::find($userId);
                $userRecord->password = bcrypt($password);
                $userRecord->save();

                return view('agency.success',array('message'=>'Your Account Activated Successfully.'));
            } else {
                return view('agency.activation',array('message'=>'Invalid Activation Link and Account already activated.'));
            }
        } else {
            return view('agency.activation',array('message'=>'Invalid Activation Link and Account already activated.'));
        }
        
    }
}

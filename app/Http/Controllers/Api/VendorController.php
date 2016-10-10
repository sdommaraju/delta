<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Models\Vendor;
use App\Http\Models\VendorGroup;
use App\Http\Models\User;
use App\Http\Requests\VendorRequest;
use App\Http\Transformers\VendorTransformer;
use App\Http\Transformers\VendorGroupTransformer;
use Dingo\Api\Auth\Auth;
use LucaDegasperi\OAuth2Server\Authorizer;
use Mail;
use App\Http\Models\Jobs;
use App\Http\Models\Agency;

class VendorController extends BaseController
{
    public function __construct(Authorizer $authorizer){
    
        $this->authorizer = $authorizer;
    }
    /**
     * @api {get} /company Fetch All Companies.
     * @apiVersion 1.0.0
     * @apiName GetCompanies
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": [
     *       {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *     ]
     *   }
     *
     * @apiError CompaniesNotFound No Companies found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CompaniesNotFound"
     *     }
     */
    public function index()
    {
        $user_id=$this->authorizer->getResourceOwnerId();
        $user = User::findorfail($user_id);
        
        $vendors = Vendor::where('agency_id','=',$user->agency_id)->get();
        return $this->response->collection($vendors, new VendorTransformer);
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
     * @api {post} /companies Create New Company.
     * @apiVersion 1.0.0
     * @apiName CreateCompany
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {String} name Company Name
     * @apiParam {String} city City
     * @apiParam {String} state State
     * @apiParam {String} contact_name Name of Contact person
     * @apiParam {String} contact_email Email of Contact person
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *   }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 500 Server Error
     *     {
     *       "error": "Can't Create Company"
     *     }
     */
    public function store(VendorRequest $request)
    {
        $user_id=$this->authorizer->getResourceOwnerId();
        $user = User::findorfail($user_id);
        
        $vendorData = $request->input();
       
        $vendorData['agency_id'] = $user->agency_id;
        $vendorData['vendor_group_id'] = $vendorData['vendor_group_id'];
        
        $vendor = Vendor::create($vendorData);
        
        return $this->response->item($vendor,new VendorTransformer);
    }

    /**
     * @api {get} /companies/:id Fetch Company Details by Id.
     * @apiVersion 1.0.0
     * @apiName GetCompany
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Company Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *   }
     *
     * @apiError CompanyNotFound No Company found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CompanyNotFound"
     *     }
     */
    public function show($id)
    {
    	$vendor = Vendor::findorfail($id);
       return $this->response->item($vendor,new VendorTransformer);
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
     * @api {put} /companies/:id Update Company Details by Id.
     * @apiVersion 1.0.0
     * @apiName PutCompany
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Company Id.
     * @apiParam {String} name Company Name
     * @apiParam {String} city City
     * @apiParam {String} state State
     * @apiParam {String} contact_name Name of Contact person
     * @apiParam {String} contact_email Email of Contact person
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *   }
     *
     * @apiError CompanyNotFound No Company found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CompanyNotFound"
     *     }
     */
    public function update(VendorRequest $request, $id)
    {
    	$vendor = Vendor::findorfail($id);
        
    	$vendorData = $request->input();
        
        $vendor->name = $vendorData['name'];
        $vendor->email_id = $vendorData['email_id'];
        $vendor->vendor_group_id = $vendorData['vendor_group_id'];
        
        $vendor->save();
        
        return $this->response->item($vendor,new VendorTransformer);
    }

    /**
     * @api {delete} /companies/:id Delete Company by Id
     * @apiVersion 1.0.0
     * @apiName DeleteCompany
     * @apiGroup Companies
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiParam {Integer} id Company Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *         "id": 1,
     *         "name": "Srinu",
     *         "city": "sdf",
     *         "state": "sdf",
     *         "contact_name": "srinu",
     *         "contact_email": "sdommaraju@innominds.com",
     *         "created_at": {
     *           "date": "-0001-11-30 00:00:00.000000",
     *           "timezone_type": 3,
     *           "timezone": "UTC"
     *         }
     *       }
     *   }
     *
     * @apiError CompanyNotFound No Company found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CompanyNotFound"
     *     }
     */
    public function destroy($id)
    {
        $vendor = Vendor::findorfail($id);
        $vendor->delete();
        return $this->response->item($vendor,new VendorTransformer);
    }
    public function sendemail(Request $request,$id)
    {
    	$groupId = $id;
    	
    	$job_id = $request['job_id'];
    	
    	$opening = Jobs::findorfail($job_id);
    	
    	$vendors = Vendor::where('vendor_group_id','=',$id)->get();
    	
    	foreach($vendors as $vendor){
    		$agency = Agency::find($vendor->agency_id);
    		$vendor_data = Vendor::find($vendor->id);
    		$mail_data['vendor'] = $vendor_data;
    		$mail_data['job'] = $opening;
    		$mail_data['agency'] = $agency;
    		//Send Account Activation Email
    		Mail::send('vendor.details', $mail_data, function($message) use ($vendor_data,$opening) {
    			$message->to($vendor_data->email_id)->subject('Delta :: Job Details :: '.$opening->title);
    		});
    	}
    	
    	$group = VendorGroup::findorfail($id);
    	return $this->response->item($group,new VendorGroupTransformer);
    	
    }
}

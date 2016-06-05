<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Models\Jobs;
use App\Http\Models\User;
use App\Http\Requests\JobsRequest;
use App\Http\Transformers\JobsTransformer;
use App\Http\Transformers\JobsCandidatesTransformer;
use Dingo\Api\Auth\Auth;
use LucaDegasperi\OAuth2Server\Authorizer;

class JobsController extends BaseController
{
    public function __construct(Authorizer $authorizer){
    
        $this->authorizer = $authorizer;
    }
    /**
     * @api {get} /jobs Fetch All Jobs.
     * @apiVersion 1.0.0
     * @apiName GetJobs
     * @apiGroup Jobs
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": [
     *       {
     *       "id": 10,
     *       "title": "Laravel Expert",
     *       "description": "Laravel Expert",
     *       "position_type": "full",
     *       "company": {
     *          "id": 1,
     *          "name": "Srinu Tech",
     *          "city": "Hyderabad",
     *          "state": "AP",
     *          "contact_name": "Srinu",
     *          "contact_email": "sdommaraju@innominds.com",
     *          "created_at": "2016-05-05 06:41:58",
     *          "updated_at": "2016-05-05 06:41:58"
     *      },
     *     "agency": {
     *       "id": 1,
     *       "name": "SrinuTech",
     *       "logo_url": "srinu.jpg",
     *       "address": "miyapur",
     *       "city": "hyderabad",
     *       "state": "ap",
     *       "zip": "500049",
     *       "email": "sri@sri.com",
     *       "phone_number": "9949290090",
     *       "created_at": "-0001-11-30 00:00:00",
     *       "updated_at": "2016-04-04 10:25:58"
     *     },
     *       "bill_rate": "45.2",
     *       "pay_rate": "40.0",
     *       "start_date": "2016-05-06",
     *       "end_date": "2017-05-06",
     *       "openings_available": "10",
     *       "max_allowed_submissions": "50",
     *       "created_at": {
     *         "date": "2016-05-06 09:06:01.000000",
     *         "timezone_type": 3,
     *         "timezone": "UTC"
     *       }
     *     }
     *     ]
     *   }
     *
     * @apiError JobsNotFound No Jobs found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "JobsNotFound"
     *     }
     */
    public function index()
    {
        $user_id=$this->authorizer->getResourceOwnerId();
        $user = User::findorfail($user_id);
        if($user->role_id==4){
            $jobs = Jobs::where('agency_id','=',$user->agency_id)->get();
        } else if($user->role_id==5){
            $jobs = Jobs::where('group_id','=',$user->group_id)->get();
        } else {
            $jobs = Jobs::All();
        }
        
        return $this->response->collection($jobs, new JobsTransformer);
    }
    
    /**
     * @api {post} /jobs Create New Job.
     * @apiVersion 1.0.0
     * @apiName CreateJob
     * @apiGroup Jobs
     * 
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * 
     * @apiParam {String} title Job Title
     * @apiParam {String} description Job Description
     * @apiParam {String} position_type Job Position. Allowed values {'contract','full'}
     * @apiParam {Integer} agency_id Agency Id
     * @apiParam {Integer} company_id Company Id
     * @apiParam {Float} bill_rate Billing Rate
     * @apiParam {Float} pay_rate Paying Rate
     * @apiParam {Date} start_date Job Starting Date
     * @apiParam {Date} end_date Job Ending Date
     * @apiParam {Integer} openings_available Number of Openings available for this job
     * @apiParam {Integer} max_allowed_submissions Number of max submittable resources for this job
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *       "id": 10,
     *       "title": "Laravel Expert",
     *       "description": "Laravel Expert",
     *       "position_type": "full",
     *       "agency_id": "18",
     *       "bill_rate": "45.2",
     *       "company_id": "1",
     *       "pay_rate": "40.0",
     *       "start_date": "2016-05-06",
     *       "end_date": "2017-05-06",
     *       "openings_available": "10",
     *       "max_allowed_submissions": "50",
     *       "created_at": {
     *         "date": "2016-05-06 09:06:01.000000",
     *         "timezone_type": 3,
     *         "timezone": "UTC"
     *       }
     *     }
     *   }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 500 Server Error
     *     {
     *       "error": "Can't Create Job"
     *     }
     */
    public function store(JobsRequest $request)
    {
        $user_id=$this->authorizer->getResourceOwnerId();
        $user = User::findorfail($user_id);
        
        $jobData = $request->input();
        $jobData['agency_id'] = $user->agency_id;
        
        $job = Jobs::create($jobData);
    
        return $this->response->item($job,new JobsTransformer);
    }
    
    /**
     * @api {get} /jobs/:id Fetch Job Details by Id.
     * @apiVersion 1.0.0
     * @apiName GetJobDetails
     * @apiGroup Jobs
     * 
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * 
     * @apiParam {Integer} id Job Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *       "id": 10,
     *       "title": "Laravel Expert",
     *       "description": "Laravel Expert",
     *       "position_type": "full",
     *       "agency_id": "18",
     *       "bill_rate": "45.2",
     *       "company_id": "1",
     *       "pay_rate": "40.0",
     *       "start_date": "2016-05-06",
     *       "end_date": "2017-05-06",
     *       "openings_available": "10",
     *       "max_allowed_submissions": "50",
     *       "created_at": {
     *         "date": "2016-05-06 09:06:01.000000",
     *         "timezone_type": 3,
     *         "timezone": "UTC"
     *       }
     *     }
     *   }
     *
     * @apiError JobNotFound No Jobs found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "JobNotFound"
     *     }
     */
    public function show($id)
    {
        $job = Jobs::findorfail($id);
        return $this->response->item($job,new JobsTransformer);
    }
    
    /**
     * @api {get} /jobs/:id/candidates Fetch Candidates Assigned to this Job.
     * @apiVersion 1.0.0
     * @apiName GetJobCandidatesDetails
     * @apiGroup Jobs
     *
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     *
     * @apiParam {Integer} id Job Id.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *   "data": {
     *   "id": 1,
     *   "title": "PHP Developer",
     *   "description": "PHP Developer",
     *   "position_type": "contract",
     *   "agency_id": 18,
     *   "bill_rate": 20,
     *   "company_id": 1,
     *   "pay_rate": 18,
     *   "start_date": "2016-05-05",
     *   "end_date": "2017-05-05",
     *   "openings_available": 10,
     *   "max_allowed_submissions": 20,
     *   "created_at": null,
     *   "candidates": 
     *     {
     *     "screening": [
     *        {
     *         "id": 7,
     *         "agency_id": 18,
     *         "first_name": "Srinu",
     *         "last_name": "1",
     *         "address1": "miyapur",
     *         "city": "hyderabad",
     *         "state": "ap",
     *         "zip": "500049",
     *         "email": "srinu1@gmail.com",
     *         "phone_number": "23423423423",
     *         "description": "",
     *         "source": "",
     *         "salary": "",
     *         "salary_range": 0,
     *         "resume_content": "",
     *         "experience": 0,
     *         "starred": 0,
     *         "created_by": 0,
     *         "miles_radius": 0,
     *         "resume_file": "",
     *         "created_at": "2016-04-15 08:18:23",
     *         "updated_at": "2016-04-15 08:18:23"
     *       }
     *     ],
     *     "phone_interview": [
     *       {
     *         "id": 8,
     *         "agency_id": 18,
     *         "first_name": "bhavani",
     *         "last_name": "d",
     *         "address1": "miyapur",
     *         "city": "hyderabad",
     *         "state": "ap[",
     *         "zip": "500049",
     *         "email": "bhavani@gmail.com",
     *         "phone_number": "8500199145",
     *         "description": "",
     *         "source": "",
     *         "salary": "",
     *         "salary_range": 0,
     *         "resume_content": "",
     *         "experience": 0,
     *         "starred": 0,
     *         "created_by": 0,
     *         "miles_radius": 0,
     *         "resume_file": "",
     *         "created_at": "2016-04-18 16:40:48",
     *         "updated_at": "2016-04-18 16:40:48"
     *       },
     *       {
     *         "id": 10,
     *         "agency_id": 18,
     *         "first_name": "srinu",
     *         "last_name": "12",
     *         "address1": "miyapur",
     *         "city": "hyderabad",
     *         "state": "ap",
     *         "zip": "500049",
     *         "email": "srinu12@gmail.com",
     *         "phone_number": "9949290090",
     *         "description": "",
     *         "source": "",
     *         "salary": "",
     *         "salary_range": 0,
     *         "resume_content": "",
     *         "experience": 0,
     *         "starred": 0,
     *         "created_by": 0,
     *         "miles_radius": 0,
     *         "resume_file": "",
     *         "created_at": "2016-04-19 09:07:16",
     *         "updated_at": "2016-04-19 09:07:16"
     *       }
     *     ],
     *     "face_to_face": [
     *       {
     *         "id": 11,
     *         "agency_id": 18,
     *         "first_name": "srinu",
     *         "last_name": "13",
     *         "address1": "miyapur",
     *         "city": "hyderabad",
     *         "state": "ap",
     *         "zip": "500049",
     *         "email": "srinu13@gmail.com",
     *         "phone_number": "3245234554",
     *         "description": "",
     *         "source": "",
     *         "salary": "",
     *         "salary_range": 0,
     *         "resume_content": "",
     *         "experience": 0,
     *         "starred": 0,
     *         "created_by": 0,
     *         "miles_radius": 0,
     *         "resume_file": "",
     *         "created_at": "2016-04-19 09:08:56",
     *         "updated_at": "2016-04-19 09:08:56"
     *       },
     *       {
     *         "id": 9,
     *         "agency_id": 18,
     *         "first_name": "srinu",
     *         "last_name": "11",
     *         "address1": "miyapur",
     *         "city": "hyderabad",
     *         "state": "AP",
     *         "zip": "500049",
     *         "email": "srinu11@gmail.com",
     *         "phone_number": "9949290090",
     *         "description": "",
     *         "source": "",
     *         "salary": "",
     *         "salary_range": 0,
     *         "resume_content": "",
     *         "experience": 0,
     *         "starred": 0,
     *         "created_by": 0,
     *         "miles_radius": 0,
     *         "resume_file": "",
     *         "created_at": "2016-04-19 09:02:14",
     *         "updated_at": "2016-04-19 09:02:14"
     *       }
     *     ],
     *     "job_offered": [],
     *     "job_accepted": [],
     *     "job_rejected": []
     *   }
     *   }
     *   }
     *
     * @apiError JobNotFound No Jobs found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "JobNotFound"
     *     }
     */
    public function getCandidates($id)
    {
        $user_id=$this->authorizer->getResourceOwnerId();
        $user = User::findorfail($user_id);
        
        $job = Jobs::findorfail($id);
        
        //$candidatesData = $job->candidates();
        
        return $this->response->item($job,new JobsCandidatesTransformer);
    }

}

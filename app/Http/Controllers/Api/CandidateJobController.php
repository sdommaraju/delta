<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Models\Candidate;
use App\Http\Models\Jobs;
use App\Http\Models\CandidateJobs;
use App\Http\Requests\CompanyRequest;
use App\Http\Transformers\CandidateJobTransformer;
use LucaDegasperi\OAuth2Server\Authorizer;
use App\Http\Models\CandidateJobsHistory;
use App\Http\Transformers\CandidateJobHistoryTransformer;

class CandidateJobController extends BaseController
{
    public function __construct(Authorizer $authorizer){
    
        $this->middleware('oauth-user');
        $this->authorizer = $authorizer;
         
    }
    /**
     * @api {get} /candidate/{id}/jobs Fetch All Jobs for given candidate.
     * @apiVersion 1.0.0
     * @apiName GetCandidateJobs
     * @apiGroup Candidates
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * 
     * @apiParam {Integer} id Candidate Id
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": [
     *       {
     *         "id": 1,
     *         "candidate": {
     *           "id": 11,
     *           "agency_id": 18,
     *           "first_name": "srinu",
     *           "last_name": "13",
     *           "address1": "miyapur",
     *           "city": "hyderabad",
     *           "state": "ap",
     *           "zip": "500049",
     *           "email": "srinu13@gmail.com",
     *           "phone_number": "3245234554",
     *           "description": "",
     *           "source": "",
     *           "salary": "",
     *           "salary_range": 0,
     *           "resume_content": "",
     *           "experience": 0,
     *           "starred": 0,
     *           "created_by": 0,
     *           "miles_radius": 0,
     *           "resume_file": "",
     *           "created_at": "2016-04-19 09:08:56",
     *           "updated_at": "2016-04-19 09:08:56"
     *         },
     *         "job": {
     *           "id": 1,
     *           "title": "PHP Developer",
     *           "description": "PHP Developer",
     *           "position_type": "contract",
     *           "agency_id": 18,
     *           "company_id": 1,
     *           "bill_rate": 20,
     *           "pay_rate": 18,
     *           "start_date": "2016-05-05",
     *           "end_date": "2017-05-05",
     *           "openings_available": 10,
     *           "max_allowed_submissions": 20
     *         },
     *         "stage": "screening",
     *         "created_at": null
     *       }
     *     ]
     *   }
     *
     * @apiError CandidateNotFound No Candidate found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CandidateNotFound"
     *     }
     */
    public function getJobs($id)
    {
        $candidate = Candidate::findorfail($id);
        
        $candidateJobs = CandidateJobs::where('candidate_id','=',$id)->get();
        
        return $this->response->collection($candidateJobs, new CandidateJobTransformer);
    }

    /**
     * @api {post} /candidate/{candidate_id}/jobs/{job_id} Assign Job to Candidate.
     * @apiVersion 1.0.0
     * @apiName AssignJob
     * @apiGroup Candidates
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     * 
     * @apiParam {Integer} candidate_id Candidate Id
     * @apiParam {Integer} job_id Job Id
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": [
     *       {
     *         "id": 1,
     *         "candidate": {
     *           "id": 11,
     *           "agency_id": 18,
     *           "first_name": "srinu",
     *           "last_name": "13",
     *           "address1": "miyapur",
     *           "city": "hyderabad",
     *           "state": "ap",
     *           "zip": "500049",
     *           "email": "srinu13@gmail.com",
     *           "phone_number": "3245234554",
     *           "description": "",
     *           "source": "",
     *           "salary": "",
     *           "salary_range": 0,
     *           "resume_content": "",
     *           "experience": 0,
     *           "starred": 0,
     *           "created_by": 0,
     *           "miles_radius": 0,
     *           "resume_file": "",
     *           "created_at": "2016-04-19 09:08:56",
     *           "updated_at": "2016-04-19 09:08:56"
     *         },
     *         "job": {
     *           "id": 1,
     *           "title": "PHP Developer",
     *           "description": "PHP Developer",
     *           "position_type": "contract",
     *           "agency_id": 18,
     *           "company_id": 1,
     *           "bill_rate": 20,
     *           "pay_rate": 18,
     *           "start_date": "2016-05-05",
     *           "end_date": "2017-05-05",
     *           "openings_available": 10,
     *           "max_allowed_submissions": 20
     *         },
     *         "stage": "screening",
     *         "created_at": null
     *       }
     *     ]
     *   }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 500 Server Error
     *     {
     *       "error": "Can't Assign Job"
     *     }
     */
    public function assignJob($candidate_id, $job_id)
    {
       $loggedInUserId = $this->authorizer->getResourceOwnerId();
       $candidate = Candidate::findorfail($candidate_id);
       $job = Jobs::findorfail($job_id);
       
       $candidate_job_data['candidate_id'] = $candidate_id;
       $candidate_job_data['job_id'] = $job_id;
       $candidate_job_data['stage'] = 'screening';
       
       $candidateJob = CandidateJobs::create($candidate_job_data);
       
       // Save into History
       $this->saveJobChangeStageHistory($candidate_id,$job_id,"", "screening",$loggedInUserId,"");
       
       return $this->response->item($candidateJob, new CandidateJobTransformer);
    }

    /**
     * @api {post} /candidate/{candidate_id}/jobs/{job_id}/change-stage Change Job Stage.
     * @apiVersion 1.0.0
     * @apiName ChangeJobStatus
     * @apiGroup Candidates
     * @apiParam (AuthorizationHeader) {String} Accept Accept value. Allowed values: "application/vnd.delta.v1+json"
     * @apiParam (AuthorizationHeader) {String} Authorization Token value (example "Bearer 4JosxlXfnoUyhGgBjAtyutO8FxIvRIADN0lp1TI2").
     *
     * @apiParam {Integer} candidate_id Candidate Id
     * @apiParam {Integer} job_id Job Id
     * @apiParam {String} stage Job Stage to change. Allowed Values {'screening','phone_interview','face_to_face','job_offered','job_accepted','job_rejected'}
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": [
     *       {
     *         "id": 1,
     *         "candidate": {
     *           "id": 11,
     *           "agency_id": 18,
     *           "first_name": "srinu",
     *           "last_name": "13",
     *           "address1": "miyapur",
     *           "city": "hyderabad",
     *           "state": "ap",
     *           "zip": "500049",
     *           "email": "srinu13@gmail.com",
     *           "phone_number": "3245234554",
     *           "description": "",
     *           "source": "",
     *           "salary": "",
     *           "salary_range": 0,
     *           "resume_content": "",
     *           "experience": 0,
     *           "starred": 0,
     *           "created_by": 0,
     *           "miles_radius": 0,
     *           "resume_file": "",
     *           "created_at": "2016-04-19 09:08:56",
     *           "updated_at": "2016-04-19 09:08:56"
     *         },
     *         "job": {
     *           "id": 1,
     *           "title": "PHP Developer",
     *           "description": "PHP Developer",
     *           "position_type": "contract",
     *           "agency_id": 18,
     *           "company_id": 1,
     *           "bill_rate": 20,
     *           "pay_rate": 18,
     *           "start_date": "2016-05-05",
     *           "end_date": "2017-05-05",
     *           "openings_available": 10,
     *           "max_allowed_submissions": 20
     *         },
     *         "stage": "screening",
     *         "created_at": null
     *       }
     *     ]
     *   }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 500 Server Error
     *     {
     *       "error": "Can't Assign Job"
     *     }
     */
    public function changeStage(Request $request,$candidate_id, $job_id)
    {
        $loggedInUserId = $this->authorizer->getResourceOwnerId();
        
        $data = $request->input();
        
        $candidate = Candidate::findorfail($candidate_id);
        $job = Jobs::findorfail($job_id);
         
        $candidateJobData = CandidateJobs::where('candidate_id',$candidate_id)->where('job_id',$job_id)->get();
        $currentStage = $candidateJobData[0]->stage;
        $candidateJob = CandidateJobs::findorfail($candidateJobData[0]->id);
        $candidateJob['stage'] = $data['stage'];
        $comments = $data['comments'];
        $candidateJob->save();
        
        // Save into History
        $this->saveJobChangeStageHistory($candidate_id,$job_id,$currentStage, $data['stage'],$loggedInUserId,$comments);
        return $this->response->item($candidateJob, new CandidateJobTransformer);
    }
    
    public function saveJobChangeStageHistory($candidate_id, $job_id,$currentStage, $stage,$stage_changed_id,$comments){
       
        
       $candidateJobData = CandidateJobs::where('candidate_id',$candidate_id)->where('job_id',$job_id)->get();
        
       $data['job_candidate_id'] = $candidateJobData[0]->id;
       $data['from_stage'] = $currentStage;
       $data['to_stage'] = $stage;
       $data['stage_changed_id'] = $stage_changed_id;
       $data['comments'] = $comments;
       
       CandidateJobsHistory::create($data);
    }
    
    public function getJobChangeStageHistory($candidate_id="",$job_id=""){
     
        if($candidate_id>0){
            if($job_id>0){
                $candidateJobData = CandidateJobs::where('candidate_id',$candidate_id)->where('job_id',$job_id)->get();
                if(count($candidateJobData)>0){
                    $history = CandidateJobsHistory::where('job_candidate_id','=',$candidateJobData[0]->id)->get();
                
                    return $this->response->collection($history, new CandidateJobHistoryTransformer);
                } else {
                    return json_encode(array('data'=>null));
                }
            } else {
                $candidateJobData = CandidateJobs::where('candidate_id',$candidate_id)->get();
                if(count($candidateJobData)>0){
                $history = CandidateJobsHistory::where('job_candidate_id','=',$candidateJobData[0]->id)->get();
                
                return $this->response->collection($history, new CandidateJobHistoryTransformer);
                } else {
                    return json_encode(array('data'=>null));
                }
            }
        }
        
    }
    
}

<?php

namespace App\Http\Controllers\Api;

use Request;

use App\Http\Requests;
use App\Http\Controllers\Api\BaseController;
use App\Http\Models\Candidate as Candidate;
use App\Http\Models\CandidateSkills;
use App\Http\Requests\CandidateRequest;
use App\Http\Transformers\CandidateTransformer;
use Dingo\Api\Auth\Auth;
use LucaDegasperi\OAuth2Server\Authorizer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Helpers\ResumeHelper as ResumeHelper;
use App\Http\Requests\CandidateSkillsRequest;
use App\Http\Transformers\CandidateSkillsTransformer;
use Illuminate\Database\Eloquent\Collection;

class CandidateController extends BaseController
{
    public function __construct(){
    
        $this->middleware('oauth-user',["except"=>["index"]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @api {post} /candidate Create New Candidate.
     * @apiVersion 1.0.0
     * @apiName CreateCandidate
     * @apiGroup Candidates
     * 
     * @apiParam {String} first_name Candidate First Name.
     * @apiParam {String} last_name Candidate Last Name.
     * @apiParam {String} email Candidate Email.
     * @apiParam {String} phone_number Phone Number.
     * @apiParam {String} address1 Address.
     * @apiParam {String} city Candidate Email.
     * @apiParam {String} state Candidate Email.
     * @apiParam {String} zip Candidate Email.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     "data": {
     *       "id": 4,
     *       "first_name": "srinu",
     *       "last_name": "sri",
     *       "email": "srinivasulumsc@gmail.com",
     *       "agency_id": "1",
     *       "city": "hyderabad",
     *       "state": "TS",
     *       "phone_number": "9949290090",
     *       "experience": null,
     *       "salary": null,
     *       "salary_range": null,
     *       "created_by": null,
     *       "miles_radius": null
     *     }
     *   }
     *
     * @apiError Error.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 500 Server Error
     *     {
     *       "error": "Can't Create Candidate"
     *     }
     */
    public function store(CandidateRequest $request)
    {
        $candidate = $request->input();
        return $this->response->item(Candidate::create($candidate),new CandidateTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidate = Candidate::findorfail($id);
        return $this->response->item($candidate,new CandidateTransformer);
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
    
    public function uploadResume(Request $request, $id)
    {
        
        $candidate = Candidate::findorfail($id);
       
        $file = Request::file('resume');
        $extension = $file->getClientOriginalExtension();
        $original_file_name = $file->getClientOriginalName();
        $file_content = File::get($file);
        $store = Storage::disk('local')->put($file->getClientOriginalName(),  $file_content);
        
        $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $stored_file = $path.$original_file_name;
        
        if($store){
            $resume = new ResumeHelper($stored_file,$extension);
            $content = $resume->parseResume();
            $candidate->resume_content = $content;
            $candidate->resume_file = $original_file_name;
            $candidate->save();
        }
        return $this->response->item($candidate,new CandidateTransformer);
        
    }
    public function uploadProfile(Request $request, $id)
    {
    
        $data = Request::all();
        $candidate = Candidate::findorfail($id);
        
        $candidate->resume = $data['profile'];
        $candidate->save();
        
        return $this->response->item($candidate,new CandidateTransformer);
    
    }
    
    public function addSkill(CandidateSkillsRequest $request, $id)
    {
    
        $candidate = Candidate::findorfail($id);
        $skill = $request->input();
        $skill['candidate_id'] = $candidate->id;
        
        $candidateSkill = CandidateSkills::create($skill);
        return $this->response->item($candidateSkill,new CandidateSkillsTransformer);
    
    }
    function search(Request $request){
        $search_data = Request::all();
        $params = json_decode($search_data['params']);
        
        $candidateSkills = new CandidateSkills();
        $candidates = $candidateSkills->searchBySkills($params->skills);
        $candidates = json_decode(json_encode($candidates), true);
        
        $candidates = Candidate::hydrate($candidates);
        return $this->response->collection($candidates,new CandidateTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api;

use Request;

use App\Http\Requests;
use App\Http\Controllers\Api\BaseController;
use App\Http\Models\Candidate;
use App\Http\Requests\CandidateRequest;
use App\Http\Transformers\CandidateTransformer;
use Dingo\Api\Auth\Auth;
use LucaDegasperi\OAuth2Server\Authorizer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Helpers\ResumeHelper as ResumeHelper;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        //
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
            $candidate->resume = $content;
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

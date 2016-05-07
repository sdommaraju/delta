<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Jobs extends Model
{
    protected $table = 'jobs';
    
    protected $fillable = ['title', 'description','agency_id','company_id','position_type','bill_rate','pay_rate','start_date','end_date','openings_available','max_allowed_submissions'];
    
    public function candidates(){
        $stages = ['screening','phone_interview','face_to_face','job_offered','job_accepted','job_rejected'];
        $candidatesData = [];
        foreach($stages as $stage){
            $query = " SELECT c.* FROM candidates c, job_candidates jc WHERE c.id=jc.candidate_id AND jc.job_id='".$this->id."' "
                      ." AND jc.stage='".$stage."' ";
           
            $result =  DB::select($query);
            $candidatesData[$stage] = $result;
        }
        return $candidatesData;
    }
    public function company(){
        return $this->hasOne('App\Http\Models\Companies','id','company_id');
    }
    public function agency(){
        return $this->hasOne('App\Http\Models\Agency','id','agency_id');
    }
}

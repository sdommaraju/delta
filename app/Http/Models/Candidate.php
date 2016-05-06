<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\CandidateSkills;
use App\Http\Models\CandidateJobs;

class Candidate extends Model
{
    protected $table = 'candidates';
    
    protected $fillable = ['agency_id', 'first_name', 'last_name','address1','city','state','zip','phone_number','email','description','resume_content'];
    
    public function skills()
    {
        return $this->hasMany('App\Http\Models\CandidateSkills','candidate_id','id');
    }
    public function jobs()
    {
        return $this->hasMany('App\Http\Models\CandidateJobs','candidate_id','id');
    }
}

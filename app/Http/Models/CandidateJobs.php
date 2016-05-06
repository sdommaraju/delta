<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Candidate;

class CandidateJobs extends Model
{
    protected $table = 'job_candidates';
    
    protected $fillable = ['candidate_id', 'job_id','state','stage'];
    
    public function candidate()
    {
        return $this->belongsTo('App\Http\Models\Candidate','candidate_id');
    }
    public function job()
    {
        return $this->belongsTo('App\Http\Models\Jobs','job_id');
    }
}

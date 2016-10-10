<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Candidate;

class CandidateJobsHistory extends Model
{
    protected $table = 'job_candidates_history';
    
    protected $fillable = ['job_candidate_id', 'from_stage','to_stage','stage_changed_id','comments'];
    
    
}

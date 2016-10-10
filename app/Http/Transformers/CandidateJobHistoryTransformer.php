<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Candidate;
use App\Http\Models\Jobs;
use App\Http\Models\User;
use App\Http\Models\CandidateJobsHistory;
use App\Http\Models\CandidateJobs;

class CandidateJobHistoryTransformer extends TransformerAbstract {
    public function transform(CandidateJobsHistory $history) {
        return [
            'id' => $history->id,
            'candidate' => $this->getJobCandidate($history->job_candidate_id,"candidate"),
            'job' => $this->getJobCandidate($history->job_candidate_id,"job"),
            'user' => $this->getChangedUser($history->stage_changed_id),
            'from_stage' => $history->from_stage,
            'to_stage' => $history->to_stage,
            'comments' => $history->comments,
            'date' => $history->created_at,
        ];
    }
    
    public function getChangedUser($user_id)
    {
        if($user_id>0){
            $user = User::find($user_id);
            
            $userData['id'] = $user->id;
            $userData['first_name'] = $user->first_name;
            $userData['last_name'] = $user->last_name;
            $userData['email'] = $user->email;
            return $userData;
        } else {
            return null;
        }
    }
    public function getJobCandidate($candidate_job_id,$type="candidate")
    {
 
        $jobCandidate = CandidateJobs::find($candidate_job_id);
        
        if($type=="candidate"){
            $candidate = Candidate::find($jobCandidate->candidate_id);
            
            $candidateData['id'] = $candidate->id;
            $candidateData['first_name'] = $candidate->first_name;
            $candidateData['last_name'] = $candidate->last_name;
            $candidateData['email'] = $candidate->email;
            return $candidateData;
        } else if($type=="job"){
            $job = Jobs::find($jobCandidate->job_id);
            
            $jobData['id'] = $job->id;
            $jobData['title'] = $job->title;
            return $jobData;
        }
    }
    
}
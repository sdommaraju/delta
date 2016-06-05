<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\CandidateJobs;
use App\Http\Models\Candidate;
use App\Http\Models\Jobs;
use App\Http\Models\Companies;
use App\Http\Transformers\CompanyTransformer;

class CandidateJobTransformer extends TransformerAbstract {
    public function transform(CandidateJobs $candidateJob) {
        return [
            'id' => $candidateJob->id,
            'candidate' => $candidateJob->candidate,
            'job' => $this->getJobCompany($candidateJob->job),
            'stage' => $candidateJob->stage,
            'created_at' => $candidateJob->created_at,
            'company' => Companies::find($candidateJob->job->company_id)
        ];
    }
    
    public function getJobCompany($job)
    {
        $job->company = Companies::find($job->company_id);
        return $job;
    }
    
}
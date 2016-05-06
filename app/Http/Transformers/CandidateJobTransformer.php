<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\CandidateJobs;
use App\Http\Models\Candidate;
use App\Http\Models\Jobs;

class CandidateJobTransformer extends TransformerAbstract {
    public function transform(CandidateJobs $candidateJob) {
        return [
            'id' => $candidateJob->id,
            'candidate' => $candidateJob->candidate,
            'job' => $candidateJob->job,
            'stage' => $candidateJob->stage,
            'created_at' => $candidateJob->created_at
        ];
    }
}
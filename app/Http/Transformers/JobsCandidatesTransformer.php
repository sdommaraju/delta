<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Jobs;

class JobsCandidatesTransformer extends TransformerAbstract {
    public function transform(Jobs $job) {
        return [
            'id' => $job->id,
            'title' => $job->title,
            'description' => $job->description,
            'position_type' => $job->position_type,
            'agency_id' => $job->agency_id,
            'bill_rate' => $job->bill_rate,
            'company_id' => $job->company_id,
            'pay_rate' => $job->pay_rate,
            'start_date' => $job->start_date,
            'end_date' => $job->end_date,
            'openings_available' => $job->openings_available,
            'max_allowed_submissions' => $job->max_allowed_submissions,
            'created_at' => $job->created_at,
            'candidates' => $job->candidates()
        ];
    }
}
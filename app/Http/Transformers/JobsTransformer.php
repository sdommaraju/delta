<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Jobs;

class JobsTransformer extends TransformerAbstract {
    public function transform(Jobs $job) {
        return [
            'id' => $job->id,
            'title' => $job->title,
            'description' => $job->description,
            'position_type' => $job->position_type,
            'bill_rate' => $job->bill_rate,
            'company' => $job->company,
            'agency' => $job->agency,
            'pay_rate' => $job->pay_rate,
            'start_date' => $job->start_date,
            'end_date' => $job->end_date,
            'openings_available' => $job->openings_available,
            'max_allowed_submissions' => $job->max_allowed_submissions,
            'created_at' => $job->created_at,
        ];
    }
}
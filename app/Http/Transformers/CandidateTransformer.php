<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Candidate;

class CandidateTransformer extends TransformerAbstract {
    public function transform(Candidate $candidate) {
        return [
            'id' => $candidate->id,
            'first_name' => $candidate->first_name,
            'last_name' => $candidate->last_name,
            'email' => $candidate->email,
            'agency_id' => $candidate->agency_id,
            'city' => $candidate->city,
            'state' => $candidate->state,
            'phone_number' => $candidate->phone_number,
            'experience' => $candidate->experience,
            'salary' => $candidate->salary,
            'salary_range' => $candidate->salary_range,
            'created_by' => $candidate->created_by,
            'miles_radius' => $candidate->miles_radius,
            'resume_file' => $candidate->resume_file,
            'created_at' => $candidate->created_at,
            'updated_at' => $candidate->updated_at,
        ];
    }
}
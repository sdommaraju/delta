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
            'email' => $candidate->email
        ];
    }
}
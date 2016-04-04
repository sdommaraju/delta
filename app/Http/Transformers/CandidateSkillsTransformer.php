<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Candidate;
use App\Http\Models\CandidateSkills;
class CandidateSkillsTransformer extends TransformerAbstract {
    public function transform(CandidateSkills $candidateSkill) {
        return [
            'id' => $candidateSkill->id,
            'candidate_id' => $candidateSkill->candidate_id,
            'skill' => $candidateSkill->skill,
            'experience' => $candidateSkill->experience
        ];
    }
}
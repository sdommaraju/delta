<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Projects;

class ProjectsTransformer extends TransformerAbstract {
    public function transform(Projects $project) {
        return [
            'id' => $project->id,
            'project_name' => $project->project_name,
            'client' => $project->client,
            'user_id' => $project->user_id
        ];
    }
}
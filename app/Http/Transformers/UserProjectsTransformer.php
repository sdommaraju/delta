<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\User;
use App\Http\Models\Projects;
use App\Http\Transformers\ProjectsTransformer;

class UserProjectsTransformer extends TransformerAbstract {
    public function transform(Projects $projects) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'projects' => $this->collection($projects, new ProjectsTransformer)
        ];
    }
}
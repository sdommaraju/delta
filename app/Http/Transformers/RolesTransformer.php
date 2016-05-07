<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Role;

class RolesTransformer extends TransformerAbstract {
    public function transform(Role $role) {
        return [
            'id' => $role->id,
            'name' => $role->name
        ];
    }
}
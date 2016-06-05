<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Role;
use App\Http\Models\Groups;
class GroupsTransformer extends TransformerAbstract {
    public function transform(Groups $group) {
        return [
            'id' => $group->id,
            'name' => $group->name,
            'agency_id' => $group->agency_id
        ];
    }
}
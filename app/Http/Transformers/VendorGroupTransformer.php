<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\VendorGroup;

class VendorGroupTransformer extends TransformerAbstract {
    public function transform(VendorGroup $group) {
        return [
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description,
            'agency_id' => $group->agency_id,
            'created_at' => $group->created_at
        ];
    }
}
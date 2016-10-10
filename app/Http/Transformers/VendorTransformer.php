<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Vendor;

class VendorTransformer extends TransformerAbstract {
    public function transform(Vendor $vendor) {
        return [
            'id' => $vendor->id,
            'name' => $vendor->name,
            'email_id' => $vendor->email_id,
            'agency_id' => $vendor->agency_id,
        	'group' => $vendor->group,
        	'vendor_group_id' => $vendor->vendor_group_id,
            'created_at' => $vendor->created_at
        ];
    }
}
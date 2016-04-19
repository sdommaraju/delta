<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Agency;

class AgencyTransformer extends TransformerAbstract {
    public function transform(Agency $agency) {
        return [
            'id' => $agency->id,
            'name' => $agency->name,
            'logo_url' => $agency->logo_url,
            'email' => $agency->email,
            'address' => $agency->address,
            'city' => $agency->city,
            'state' => $agency->state,
            'phone_number' => $agency->phone_number,
            'zip' => $agency->zip,
            'created_at' => $agency->created_at,
            'agencyAdmin' => $agency->userAdmin
        ];
    }
}
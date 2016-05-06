<?php 

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Http\Models\Companies;

class CompanyTransformer extends TransformerAbstract {
    public function transform(Companies $company) {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'city' => $company->city,
            'state' => $company->state,
            'contact_name' => $company->contact_name,
            'contact_email' => $company->contact_email,
            'created_at' => $company->created_at
        ];
    }
}
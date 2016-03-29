<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CandidateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method())
        {
            case 'POST' :
                {
                    return [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'email' => 'required|email',
                        'phone_number' => 'required',
                        'agency_id' => 'required',
                        'address1' => 'required',
                        'city' => 'required',
                        'state' => 'required',
                        'zip' => 'required',
                    ];
                }
                
            default:break;
        }
        
    }

}

<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AgencyRequest extends Request
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
            case 'PUT' :
                {
                    return [
                        'name' => 'required',
                        'logo_url' => 'required',
                        'address' => 'required',
                        'city' => 'required',
                        'state' => 'required',
                        'zip' => 'required',
                        'phone_number' => 'required'
                    ];
                }
            case 'POST' :
                {
                    return [
                        'name' => 'required',
                        'logo_url' => 'required',
                        'address' => 'required',
                        'city' => 'required',
                        'state' => 'required',
                        'zip' => 'required',
                        'phone_number' => 'required',
                        'email' => 'required|email'
                    ];
                }   
            default:break;
        }
        
    }

}

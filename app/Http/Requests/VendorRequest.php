<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class VendorRequest extends Request
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
                    	'email_id' => 'required|email',
                    	'vendor_group_id' => 'required'
                    ];
                }
            case 'POST' :
                {
                    return [
                        'name' => 'required',
                    	'email_id' => 'required|email',
                    	'vendor_group_id' => 'required'
                    ];
                }   
            default:break;
        }
        
    }

}

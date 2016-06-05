<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class JobsRequest extends Request
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
                        'title' => 'required',
                        'position_type' => 'required',
                        //'agency_id' => 'required',
                        'company_id' => 'required',
                        'start_date' => 'required'
                    ];
                }   
            default:break;
        }
        
    }

}

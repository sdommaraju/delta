<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Http\Models\UserGallery;
class UserGalleryRequest extends Request
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
                        'user_id' => 'required',
                        'file' => 'required',
                    ];
                }
            default:break;
        }
    }
}

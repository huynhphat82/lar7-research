<?php

namespace App\Validation\Requests\Apis;

use App\Validation\Validator;

class GetApiTestApiRequest extends Validator
{
    public function rules()
    {
        return [
            'required'
        ];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }
}

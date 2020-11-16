<?php

namespace App\Validation\Requests;

use App\Validation\Validator;

class GetTestTestValidationRequest extends Validator
{
    public function rules()
    {
        return [
            'test' => 'required'
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

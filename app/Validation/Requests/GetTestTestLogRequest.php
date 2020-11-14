<?php

namespace App\Validation\Requests;

use App\Validation\Validator;

class GetTestTestLogRequest extends Validator
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
<?php

namespace App\Validation\Requests\Apis;

use App\Validation\Validator;

class GetApiTestApiRequest extends Validator
{
    /**
     * @override
     * Define rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'test' => 'checkbox_force'
        ];
    }

    /**
     * Customize error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'test.required' => 'Field :attribute is required.'
        ];
    }

    /**
     * Customize field names
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'test' => 'email'
        ];
    }
}

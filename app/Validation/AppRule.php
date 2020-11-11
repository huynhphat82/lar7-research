<?php

namespace App\Validation;

use Illuminate\Support\Facades\Validator;

class AppRule
{
    /**
     * Register more validation rules for app
     *
     * @return void
     */
    public static function register()
    {
        // Define new rule
        // extend(): only applied when attribute must be present & its value must not empty string
        Validator::extend('foo', function ($attribute, $value, $parameters, $validator) {
            return $value == 'foo';
        });
        // Define custom placeholder replacements for error messages
        Validator::replacer('foo', function ($message, $attribute, $rule, $parameters) {
            //return str_replace(...);
        });

        // Define new rule
        // extendImplicit(): applied even when attribute is empty (as required rule)
        Validator::extendImplicit('bar', function ($attribute, $value, $parameters, $validator) {
            return $value == 'bar';
        });
    }
}

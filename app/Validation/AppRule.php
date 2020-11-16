<?php

namespace App\Validation;

use App\Traits\Mixin;
use Illuminate\Support\Facades\Validator;

/**
 * Define more rules for validation
 */
class AppRule
{
    use Mixin;

    /**
     * Register more validation rules for app
     *
     * @return void
     */
    private function register()
    {
        // Define new rule
        // extend(): only applied when attribute must be present & its value must not empty string
        Validator::extend('foo', function ($attribute, $value, $parameters, $validator) {
            return $value == 'foo';
        });
        // Define custom placeholder replacements for error messages
        Validator::replacer('foo', function ($message, $attribute, $rule, $parameters) {
            return str_replace('foo', 'replace_foo_by_fooing', $message);
        });

        // Define new rule
        // extendImplicit(): applied even when attribute is empty (as required rule)
        Validator::extendImplicit('bar_force', function ($attribute, $value, $parameters, $validator) {
            return $value == 'bar';
        });

        Validator::replacer('bar_force', function ($message, $attribute, $rule, $parameters) {
            return str_replace('bar_force', "replaced-{$attribute}", $message);
        });
    }

}

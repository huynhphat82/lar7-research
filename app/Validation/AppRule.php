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
        Validator::extend('checkbox', function ($attribute, $value, $parameters, $validator) {
            return in_array($value, ['yes', 'on', 1, 'true', true]);
        });
        // Define custom placeholder replacements for error messages
        Validator::replacer('checkbox', function ($message, $attribute, $rule, $parameters) {
            return str_replace('checkbox', 'checkbox field', $message);
        });
        Validator::extend('hhmm', function ($attribute, $value, $parameters, $validator) {
            $delimeter = $parameters[0] ?? ':';
            $minHour = $parameters[1] ?? 1;
            $maxHour = $parameters[2] ?? 23;
            $minMins = $parameters[3] ?? 0;
            $maxMins = 59;

            $isValid = false;
            $pattern = "#(?:[01]|2(?![4-9])){1}\d{1}{$delimeter}[0-5]{1}\d{1}#i"; // 23:59
            if ($maxHour > 23) {
                $pattern = "#\d{2}{$delimeter}[0-5]{1}\d{1}#i"; // 26:59
            }
            if (preg_match($pattern, $value) === 1) {
                $isValid = true;
                if ($maxHour > 23) {
                    $splits = explode($delimeter, $value);
                    $hour = (int) $splits[0];
                    $mins = (int) $splits[1];
                    $isValid =
                        $hour >= $minHour &&
                        $hour <= $maxHour &&
                        $mins >= $minMins &&
                        $mins <= $maxMins;
                }
            }
            return $isValid;
        });

        // Define new rule
        // extendImplicit(): applied even when attribute is empty (as required rule)
        Validator::extendImplicit('checkbox_force', function ($attribute, $value, $parameters, $validator) {
            return in_array($value, ['yes', 'on', 1, 'true', true]);
        });

        Validator::replacer('checkbox_force', function ($message, $attribute, $rule, $parameters) {
            return str_replace('checkbox_force', "checkbox field", $message);
        });
    }

}

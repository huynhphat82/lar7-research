<?php

if (!function_exists('isApi')) {

    /**
     * Check url whether it is api
     *
     * @return bool
     */
    function isApi()
    {
        $prefix = Constant::PREFIX_API;
        return request()->is("{$prefix}*"); // preg_match("#^{$prefix}#i", request()->path());
    }
}

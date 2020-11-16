<?php

namespace App\Traits;

trait Mixin
{
    /**
     * __call
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        return $this->$method(...$arguments);
    }

    /**
     * __callStatic
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments = [])
    {
        return (new static)->$method(...$arguments);
    }
}

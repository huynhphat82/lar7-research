<?php

namespace Tests\Common\Traits;

Trait Http
{
    public function __call($method, $args = [])
    {
        return $this->json(strtoupper($method), ...$args);
    }
}

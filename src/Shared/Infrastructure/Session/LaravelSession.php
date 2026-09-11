<?php

namespace AMovil\Shared\Infrastructure\Session;

use AMovil\Shared\Domain\Session\Session;

class LaravelSession implements Session
{
    public function set($key, $value)
    {
        session([$key => $value]);
    }

    public function setAll(array $values)
    {
        session($values);
    }

    public function get($key)
    {
        return session($key);
    }

    public function invalidate()
    {
        session()->invalidate();
    }
}

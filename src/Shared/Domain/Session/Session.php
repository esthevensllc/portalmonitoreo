<?php

namespace AMovil\Shared\Domain\Session;

interface Session
{
    public function set($key, $value);
    public function setAll(array $values);
    public function get($key);
    public function invalidate();
}

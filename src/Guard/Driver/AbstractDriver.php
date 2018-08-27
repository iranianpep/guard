<?php

namespace Guard\Driver;

abstract class AbstractDriver implements DriverInterface
{
    function block($entity, $value): bool
    {
        if ($this->exists($entity, $value) === false) {
            return $this->write($entity, $value);
        }

        return false;
    }

    function isBlocked($entity, $value): bool
    {
        if ($this->exists($entity, $value) === true) {
            return true;
        }

        return false;
    }

    abstract protected function write($entity, $value);
    abstract protected function exists($entity, $value): bool;
}

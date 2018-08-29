<?php

namespace Guard\Driver;

abstract class AbstractDriver implements DriverInterface
{
    public function block($entity, $value): bool
    {
        if ($this->exists($entity, $value) === false) {
            return $this->add($entity, $value);
        }

        return false;
    }

    public function isBlocked($entity, $value): bool
    {
        if ($this->exists($entity, $value) === true) {
            return true;
        }

        return false;
    }

    public function unBlock($entity, $value): bool
    {
        if ($this->exists($entity, $value) === true) {
            return $this->remove($entity, $value);
        }

        return false;
    }

    abstract protected function add($entity, $value);
    abstract protected function exists($entity, $value): bool;
    abstract protected function remove($entity, $value);
}

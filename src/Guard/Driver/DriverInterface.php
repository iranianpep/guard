<?php

namespace Guard\Driver;

interface DriverInterface
{
    public function block($entity, $value) : bool;
    public function isBlocked($entity, $value): bool;
}

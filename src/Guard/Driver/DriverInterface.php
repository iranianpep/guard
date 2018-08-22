<?php

namespace Guard\Driver;

interface DriverInterface
{
    function block($entity, $value) : bool;
    function isBlocked($entity, $value): bool;
}

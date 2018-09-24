<?php

namespace Guard\Driver;

interface DriverInterface
{
    /**
     * Block a value for an entity.
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    public function block($entity, $value) : bool;

    /**
     * Unblock a blocked value for an entity.
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    public function unBlock($entity, $value): bool;

    /**
     * Check if a value for an entity is blocked.
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    public function isBlocked($entity, $value): bool;
}

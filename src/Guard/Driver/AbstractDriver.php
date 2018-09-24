<?php

namespace Guard\Driver;

abstract class AbstractDriver implements DriverInterface
{
    /**
     * Add an entity with its value.
     *
     * @param $entity
     * @param $value
     *
     * @return mixed
     */
    abstract protected function add($entity, $value);

    /**
     * Check whether an entity with its value exists.
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    abstract protected function exists($entity, $value): bool;

    /**
     * Remove an entity with its value from the collection.
     *
     * @param $entity
     * @param $value
     *
     * @return mixed
     */
    abstract protected function remove($entity, $value);

    /**
     * Block a value for an entity.
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    public function block($entity, $value): bool
    {
        if ($this->exists($entity, $value) === false) {
            return $this->add($entity, $value);
        }

        return false;
    }

    /**
     * Unblock a blocked value for an entity.
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    public function unBlock($entity, $value): bool
    {
        if ($this->exists($entity, $value) === true) {
            return $this->remove($entity, $value);
        }

        return false;
    }

    /**
     * Check if a value for an entity is blocked.
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    public function isBlocked($entity, $value): bool
    {
        if ($this->exists($entity, $value) === true) {
            return true;
        }

        return false;
    }
}

<?php

namespace Guard;

use Guard\Driver\DriverInterface;

class Guard
{
    private $drivers;

    /**
     * Guard constructor.
     *
     * @param array $drivers
     */
    public function __construct(array $drivers = [])
    {
        $this->setDrivers($drivers);
    }

    /**
     * Block a value for an entity
     *
     * @param $entity
     * @param $value
     */
    public function block($entity, $value)
    {
        // load all the pushed drivers and call block
        foreach ($this->getDrivers() as $driver) {
            $driver->block($entity, $value);
        }
    }

    /**
     * Unblock a blocked value for an entity
     *
     * @param $entity
     * @param $value
     */
    public function unBlock($entity, $value)
    {
        // load all the pushed drivers and call unblock
        foreach ($this->getDrivers() as $driver) {
            $driver->unBlock($entity, $value);
        }
    }

    /**
     * Check if a value for an entity is blocked
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    public function isBlocked($entity, $value)
    {
        // load all the pushed drivers and call isBlocked
        foreach ($this->getDrivers() as $driver) {
            if ($driver->isBlocked($entity, $value) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Push a driver to drivers list
     *
     * @param DriverInterface $driver
     * @return Guard
     */
    public function pushDriver(DriverInterface $driver): self
    {
        $this->drivers[] = $driver;

        return $this;
    }

    /**
     * Get the drivers
     *
     * @return DriverInterface[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * Set the drivers
     *
     * @param DriverInterface[] $drivers
     * @return Guard
     */
    public function setDrivers(array $drivers): self
    {
        $this->drivers = [];
        foreach ($drivers as $driver) {
            $this->pushDriver($driver);
        }

        return $this;
    }
}

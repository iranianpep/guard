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
     * @param DriverInterface $driver
     * @return Guard
     */
    public function pushDriver(DriverInterface $driver): self
    {
        $this->drivers[] = $driver;

        return $this;
    }

    /**
     * @return DriverInterface[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
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

<?php

namespace Guard;

use Guard\Driver\DriverInterface;

class Guard
{
    private $drivers;

    // block
    public function block()
    {
        // load all the pushed drivers and call block
    }

    // check isBlocked
    public function isBlocked()
    {
        // load all the pushed drivers and call isBlocked
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

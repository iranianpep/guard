<?php

namespace Guard;

use Guard\Driver\MongoDBDriver;
use PHPUnit\Framework\TestCase;

class GreetingTest extends TestCase
{
    public function testPushDatabaseDriver()
    {
        $guard = new Guard();

        $driver1 = new MongoDBDriver();
        $driver1->test = 1;

        $driver2 = new MongoDBDriver();
        $driver2->test = 2;

        $guard->pushDriver($driver1)->pushDriver($driver2);
        $drivers = $guard->getDrivers();

        $this->assertEquals([$driver1, $driver2], $drivers);
        $this->assertEquals(1, $drivers[0]->test);
        $this->assertEquals(2, $drivers[1]->test);
    }
}

<?php

namespace Guard;

use Guard\Driver\MongoDBDriver;
use Guard\Driver\MongoDBDriverTest;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class GreetingTest extends TestCase
{
    public function testPushDriver()
    {
        $guard = new Guard();

        $driver1 = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $driver1->test = 1;

        $driver2 = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $driver2->test = 2;

        $guard->pushDriver($driver1)->pushDriver($driver2);
        $drivers = $guard->getDrivers();

        $this->assertEquals([$driver1, $driver2], $drivers);
        $this->assertEquals(1, $drivers[0]->test);
        $this->assertEquals(2, $drivers[1]->test);
    }

    public function testSetDrivers()
    {
        $guard = new Guard();

        $driver1 = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $driver1->test = 1;

        $driver2 = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $driver2->test = 2;

        $drivers = [$driver1, $driver2];
        $guard->setDrivers($drivers);

        $this->assertEquals($drivers, $guard->getDrivers());
    }

    public function testIsBlockedNoDriver()
    {
        $guard = new Guard();
        $this->assertFalse($guard->isBlocked('dummy', 'dummy'));
    }

    public function testBlock()
    {
        $guard = new Guard();

        // mock insertOne function
        $mock = $this->createMock(MongoDBDriverTest::MONGO_COLLECTION_CLASS);
        $mock->expects($this->once())
            ->method('insertOne')
            ->with(MongoDBDriverTest::SAMPLE_DATA[1]['args'])
            ->willReturn(true);

        $mock->expects($this->once())
            ->method('findOne')
            ->with(MongoDBDriverTest::SAMPLE_DATA[1]['args'])
            ->willReturn(MongoDBDriverTest::SAMPLE_DATA[1]['exists']);

        $driver1 = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $driver1->setCollection($mock);
        $guard->pushDriver($driver1);

        $guard->block(
            MongoDBDriverTest::SAMPLE_DATA[1]['args']['entity'],
            MongoDBDriverTest::SAMPLE_DATA[1]['args']['value']
        );
    }

    public function testBlockReturnFalse()
    {
        $guard = new Guard();

        // mock insertOne function
        $mock = $this->createMock(MongoDBDriverTest::MONGO_COLLECTION_CLASS);

        $mock->expects($this->once())
            ->method('findOne')
            ->with(MongoDBDriverTest::SAMPLE_DATA[0]['args'])
            ->willReturn(MongoDBDriverTest::SAMPLE_DATA[0]['exists']);

        $driver1 = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $driver1->setCollection($mock);
        $guard->pushDriver($driver1);

        $guard->block(
            MongoDBDriverTest::SAMPLE_DATA[0]['args']['entity'],
            MongoDBDriverTest::SAMPLE_DATA[0]['args']['value']
        );
    }

    public function testIsBlocked()
    {
        // mock findOne function
        $mock = $this->createMock(MongoDBDriverTest::MONGO_COLLECTION_CLASS);

        $args = ['entity' => 'ip', 'value' => '1.2.3.4'];
        $args1 = ['entity' => 'ip', 'value' => '1.2.3.41'];

        $mock->expects($this->exactly(2))
            ->method('findOne')
            ->with($this->logicalOr(
                $this->equalTo($args),
                $this->equalTo($args1)
            ))
            ->will($this->returnCallback([$this, 'fakeFindOne']));

        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $mongoDBDriver->setCollection($mock);

        $guard = new Guard();
        $guard->pushDriver($mongoDBDriver);

        try {
            $guard->isBlocked('ip', '1.2.3.4');
            $guard->isBlocked('ip', '1.2.3.41');
        } catch (\Exception $e) {
            // Error should not be thrown if functions are working as expected
            $this->assertFalse(true);
        }
    }

    public function testUnBlock()
    {
        // mock findOne function
        $mock = $this->createMock(MongoDBDriverTest::MONGO_COLLECTION_CLASS);

        $args = ['entity' => 'ip', 'value' => '1.2.3.4'];
        $args1 = ['entity' => 'ip', 'value' => '1.2.3.41'];

        $mock->expects($this->exactly(2))
            ->method('findOne')
            ->with($this->logicalOr(
                $this->equalTo($args),
                $this->equalTo($args1)
            ))
            ->will($this->returnCallback([$this, 'fakeFindOne']));

        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $mongoDBDriver->setCollection($mock);

        $guard = new Guard();
        $guard->pushDriver($mongoDBDriver);

        try {
            $guard->unBlock('ip', '1.2.3.41');
            $guard->unBlock('ip', '1.2.3.4');
        } catch (\Exception $e) {
            // Error should not be thrown if functions are working as expected
            $this->assertFalse(true);
        }
    }

    public function fakeFindOne($args)
    {
        foreach (MongoDBDriverTest::SAMPLE_DATA as $data) {
            if ($args === $data['args']) {
                return $data['exists'];
            }
        }
    }
}

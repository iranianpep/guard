<?php

namespace Guard\Driver;

use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class MongoDBDriverTest extends TestCase
{
    public function testExists()
    {
        // mock findOne function
        $mock = $this->createMock('MongoDB\Collection');

        $args = [
            'entity' => 'ip',
            'value' => '1.2.3.4'
        ];

        $args1 = [
            'entity' => 'ip',
            'value' => '1.2.3.41'
        ];

        $mock->expects($this->exactly(2))
            ->method('findOne')
            ->with($this->logicalOr(
                $this->equalTo($args),
                $this->equalTo($args1)
            ))
            ->will($this->returnCallback([$this, 'myCallback']));

        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $mongoDBDriver->setCollection($mock);


        $this->assertTrue($mongoDBDriver->isBlocked('ip', '1.2.3.4'));
        $this->assertFalse($mongoDBDriver->isBlocked('ip', '1.2.3.41'));
    }

    public function testWrite()
    {
        // mock insertOne function
        $mock = $this->createMock('MongoDB\Collection');

        $args = [
            'entity' => 'ip',
            'value' => '1.2.3.4'
        ];

        $args1 = [
            'entity' => 'ip',
            'value' => '1.2.3.41'
        ];

        $mock->expects($this->once())
            ->method('insertOne')
            ->with($args)
            ->willReturn(true);

        $mock->expects($this->exactly(2))
            ->method('findOne')
            ->with($this->logicalOr(
                $this->equalTo($args),
                $this->equalTo($args1)
            ))
            ->will($this->returnCallback([$this, 'myCallback']));

        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $mongoDBDriver->setCollection($mock);

        $this->assertTrue($mongoDBDriver->block('ip', '1.2.3.4'));
        $this->assertFalse($mongoDBDriver->block('ip', '1.2.3.41'));
    }

    public function myCallback($exargs)
    {
        $args = [
            'entity' => 'ip',
            'value' => '1.2.3.4'
        ];

        $args1 = [
            'entity' => 'ip',
            'value' => '1.2.3.41'
        ];

        if ($exargs === $args) {
            return true;
        }

        if ($exargs === $args1) {
            return false;
        }
    }
}

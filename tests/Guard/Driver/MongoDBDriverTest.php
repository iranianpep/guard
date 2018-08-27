<?php

namespace Guard\Driver;

use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class MongoDBDriverTest extends TestCase
{
    public function testExists()
    {
        // mock find function
        //$mock = \Mockery::mock('\MongoClient');
        //$mock->shouldReceive('vertical->primaryMember')->andReturn($mockMongoCollection);
        $mock = $this->createMock('MongoDB\Collection');

//        $mock = $this->getMockBuilder('MongoDB\Collection')
//            ->setMethods(array('findOne')) // this line tells mock builder which methods should be mocked
//            ->disableOriginalConstructor()
//            ->getMock();

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
            ->will($this->returnCallback(array($this, 'myCallback')));

//        $mock->method('findOne')
//            ->with($args1)
//            ->will($this->returnValue(false));
        //var_dump($mock->find());exit;



//        $mock->expects($this->once())
//            ->method('find')
//            ->with($args)
//            ->willReturn(['found' => true]);

        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $mongoDBDriver->setCollection($mock);


        $this->assertTrue($mongoDBDriver->isBlocked('ip', '1.2.3.4'));
        $this->assertFalse($mongoDBDriver->isBlocked('ip', '1.2.3.41'));
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

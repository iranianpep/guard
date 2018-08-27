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

        $args = [
            'entity' => 'ip',
            'value' => '1.2.3.4'
        ];

        $mock->method('find')
            ->with($args)
            ->willReturn(['found' => true]);


        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');



        $this->assertTrue($mongoDBDriver->isBlocked('ip', '1.2.3.4'));
    }
}

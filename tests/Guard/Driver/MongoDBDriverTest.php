<?php

namespace Guard\Driver;

use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class MongoDBDriverTest extends TestCase
{
    const MONGO_COLLECTION_CLASS = 'MongoDB\Collection';
    const SAMPLE_DATA = [
        [
            'args' => [
                'entity' => 'ip',
                'value' => '1.2.3.4'
            ],
            'exists' => true
        ],
        [
            'args' => [
                'entity' => 'ip',
                'value' => '1.2.3.41'
            ],
            'exists' => false
        ],
        [
            'args' => [
                'entity' => 'ip',
                'value' => '1.2.3.42'
            ],
            'exists' => false
        ]
    ];

    public function testExists()
    {
        // mock findOne function
        $mock = $this->createMock(self::MONGO_COLLECTION_CLASS);

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
            ->will($this->returnCallback([$this, 'fakeFindOne']));

        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $mongoDBDriver->setCollection($mock);


        $this->assertTrue($mongoDBDriver->isBlocked('ip', '1.2.3.4'));
        $this->assertFalse($mongoDBDriver->isBlocked('ip', '1.2.3.41'));
    }

    public function testWrite()
    {
        // mock insertOne function
        $mock = $this->createMock(self::MONGO_COLLECTION_CLASS);

        $mock->expects($this->once())
            ->method('insertOne')
            ->with(self::SAMPLE_DATA[1]['args'])
            ->willReturn(true);

        $mock->expects($this->once())
            ->method('findOne')
            ->with(self::SAMPLE_DATA[1]['args'])
            ->will($this->returnCallback([$this, 'fakeFindOne']));

        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $mongoDBDriver->setCollection($mock);

        $this->assertTrue(
            $mongoDBDriver->block(
                self::SAMPLE_DATA[1]['args']['entity'],
                self::SAMPLE_DATA[1]['args']['value']
            )
        );
    }

    public function testWriteWithFalse()
    {
        // mock insertOne function
        $mock = $this->createMock(self::MONGO_COLLECTION_CLASS);

        $mock->expects($this->once())
            ->method('insertOne')
            ->with(self::SAMPLE_DATA[1]['args'])
            ->willThrowException(new \Exception());

        $mock->expects($this->once())
            ->method('findOne')
            ->with(self::SAMPLE_DATA[1]['args'])
            ->will($this->returnCallback([$this, 'fakeFindOne']));

        $mongoDBDriver = new MongoDBDriver(new Client(), 'test_db', 'test_collection');
        $mongoDBDriver->setCollection($mock);

        $this->assertFalse(
            $mongoDBDriver->block(
                self::SAMPLE_DATA[1]['args']['entity'],
                self::SAMPLE_DATA[1]['args']['value']
            )
        );
    }

    public function fakeFindOne($args)
    {
        foreach (self::SAMPLE_DATA as $data) {
            if ($args === $data['args']) {
                return $data['exists'];
            }
        }
    }
}

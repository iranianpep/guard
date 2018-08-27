<?php

namespace Guard\Driver;

use MongoDB\Client;
use MongoDB\Collection;

class MongoDBDriver extends AbstractDriver
{
    private $collection;

    public function __construct(Client $client, $database, $collection)
    {
        $this->collection = $client->selectCollection($database, $collection);

        if (!$this->collection instanceof Collection) {
            throw new \Exception('Unable to select the collection');
        }
    }

    protected function write($entity, $value)
    {
        try {
            $this->collection->insertOne([
                'entity' => $entity,
                'value' => $value
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function exists($entity, $value): bool
    {
        $result = $this->collection->find([
            'entity' => $entity,
            'value' => $value
        ])->toArray();

        if (!empty($result)) {
            return true;
        }

        return false;
    }
}

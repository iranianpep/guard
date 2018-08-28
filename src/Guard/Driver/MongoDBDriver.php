<?php

namespace Guard\Driver;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Driver\Cursor;

class MongoDBDriver extends AbstractDriver
{
    private $collection;

    public function __construct(Client $client, $database, $collection)
    {
        $this->setCollection($client->selectCollection($database, $collection));
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * @param Collection $collection
     */
    public function setCollection(Collection $collection)
    {
        $this->collection = $collection;
    }

    protected function write($entity, $value)
    {
        try {
            $this->getCollection()->insertOne([
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
        $result = $this->getCollection()->findOne([
            'entity' => $entity,
            'value' => $value
        ]);

        if (!empty($result)) {
            return true;
        }

        return false;
    }
}

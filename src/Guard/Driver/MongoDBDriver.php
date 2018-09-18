<?php

namespace Guard\Driver;

use MongoDB\Client;
use MongoDB\Collection;

class MongoDBDriver extends AbstractDriver
{
    private $collection;

    /**
     * MongoDBDriver constructor.
     *
     * @param Client $client
     * @param        $database
     * @param        $collection
     */
    public function __construct(Client $client, $database, $collection)
    {
        $this->setCollection($client->selectCollection($database, $collection));
    }

    /**
     * Get MongoDB collection
     *
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * Set MongoDB collection
     *
     * @param Collection $collection
     */
    public function setCollection(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Add an entity with its value to the collection
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    protected function add($entity, $value)
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

    /**
     * Check whether an entity with its value exists in the collection
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
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

    /**
     * Remove an entity with its value from the collection
     *
     * @param $entity
     * @param $value
     *
     * @return bool
     */
    protected function remove($entity, $value)
    {
        try {
            $this->getCollection()->deleteOne([
                'entity' => $entity,
                'value' => $value
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

<?php

namespace Guard\Driver;

class MongoDBDriver extends AbstractDriver
{
    public function __construct(\MongoClient $mongodb, $database, $collection)
    {
        $mongodb->selectCollection($database, $collection);
    }

    protected function write($entity, $value): bool
    {
        // TODO: Implement write() method.
    }

    protected function exists($entity, $value): bool
    {
        // TODO: Implement exists() method.
    }
}

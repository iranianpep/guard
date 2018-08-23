<?php
/**
 * Created by PhpStorm.
 * User: ehsan.abbasi
 * Date: 23/8/18
 * Time: 4:32 PM
 */

namespace Guard;

use Guard\Driver\AbstractDriver;

class DummyDriver extends AbstractDriver
{
    protected function write($entity, $value): bool
    {
        // TODO: Implement write() method.
    }

    protected function exists($entity, $value): bool
    {
        if ($entity === 'exists' && $value === 'exists') {
            return true;
        }

        return false;
    }
}

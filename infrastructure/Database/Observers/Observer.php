<?php

namespace Infrastructure\Database\Observers;

abstract class Observer
{
    public function fire($class, $object)
    {
        return event(
            $event = new $class($object)
        );
    }
}

<?php

namespace App\Serializer;


class EntityCircularReferenceHandler
{
    public function __invoke($object)
    {
        return $object->id;
    }
}
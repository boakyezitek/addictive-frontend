<?php


namespace App\Services\RevenueCat\Objects;

class BaseObject
{
    public function toArray()
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();
        $result = [];
        foreach($properties as $property) {
            $getterMethod = 'get'.ucfirst($property->name);
            $result[$property->name] = $this->$getterMethod();
        }
        return $result;
    }
}

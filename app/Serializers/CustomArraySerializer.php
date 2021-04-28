<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class CustomArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return ($resourceKey) ? [$resourceKey => $data] : $data;
    }

    /**
     * Serialize a item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return ($resourceKey) ? [$resourceKey => $data] : $data;
    }

    public function null()
    {
        return null;
    }
}

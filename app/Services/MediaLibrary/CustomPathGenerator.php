<?php

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     *
     * @param Media $media
     *
     * @return string
     */
    public function getPath(Media $media) : string
    {
        return $media->collection_name . '/' . md5($media->id) . '/';
    }
    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     *
     * @param Media $media
     *
     * @return string
     */
    public function getPathForConversions(Media $media) : string
    {
        return $this->getPath($media) . 'conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/responsive-images/';
    }
}

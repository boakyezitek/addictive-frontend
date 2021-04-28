<?php

namespace App\Services\MediaLibrary;

use DateTimeInterface;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGenerator;
use Spatie\MediaLibrary\Support\UrlGenerator\BaseUrlGenerator;

class GcsUrlGenerator extends BaseUrlGenerator implements UrlGenerator
{
    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getUrl() : string
    {
        return config('media-library.gcs.domain') . '/' . $this->getPathRelativeToRoot();
    }

    /**
     * Get the temporary url for a media item.
     *
     * @param \DateTimeInterface $expiration
     * @param array $options
     *
     * @return string
     */
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        return Storage::disk('gcs')->getAdapter()->getBucket()->object($this->getPath())->signedUrl($expiration);
    }

    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getPathRelativeToRoot();
    }

    /**
     * Get the url to the directory containing responsive images.
     *
     * @return string
     */
    public function getResponsiveImagesDirectoryUrl(): string
    {
        return $this->getBaseMediaDirectoryUrl().'/'.$this->pathGenerator->getPathForResponsiveImages($this->media);
    }
}

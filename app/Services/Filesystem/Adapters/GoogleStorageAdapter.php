<?php

namespace App\Services\Filesystem\Adapters;

use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter as GCSAdapter;

class GoogleStorageAdapter extends GCSAdapter
{
    /**
     * {@inheritdoc}
     */
    public function deleteDir($dirname)
    {
        $objects = $this->listContents($dirname, true);

        // We first delete the file, so that we can delete
        // the empty folder at the end.
        uasort($objects, function ($a, $b) {
            return $b['type'] === 'file' ? 1 : -1;
        });

        // We remove all objects that should not be deleted.
        $filtered_objects = array_filter($objects, function ($object) use ($dirname) {
            return strpos($object['path'], $dirname) !== false;
        }, true);

        // Execute deletion for each object.
        foreach ($filtered_objects as $object) {
            $path = $object['path'];

            if ($object['type'] === 'dir') {
                $path = $this->normaliseDirName($path);
            }

            $this->delete($path);
        }

        return true;
    }
}

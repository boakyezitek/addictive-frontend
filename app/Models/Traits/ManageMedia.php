<?php

namespace App\Models\Traits;

use Barryvdh\Debugbar\Facade as DebugBar;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ManageMedia
{
    /**
     * Add an uploaded file to gallery.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param mixed $collection
     */
    public function addUploadedMedia(UploadedFile $file, $collection = 'cover', $deleteOthers = true)
    {
        $extension = empty($file->getClientOriginalExtension()) ? 'jpg' : $file->getClientOriginalExtension();
        $file_name = uniqid() . '.' . $extension;

        if ($deleteOthers) {
            $medias = $this->getMedia($this->table.'/'.$collection);
            foreach ($medias as $media) {
                $media->delete();
            }
        }

        list($width, $height) = getimagesize($file);

        $image = $this->addMedia($file)
            ->usingFileName($file_name)
            ->toMediaCollection($this->table.'/'.$collection);
        $image->save();

        return $image;
    }

    /**
     * Return list of medias from the gallery.
     *
     * @param mixed $collection
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUploadedMedia($collection = 'cover')
    {
        return $this->getMedia($this->table.'/'.$collection)->last();
    }


    public function deleteUploadedMedia($collection = 'cover')
    {
        return $this->getFirstMedia($this->table.'/'.$collection)->delete();
    }

    public function scopeHasMediaInCollection($query, $collection = null)
    {
        if(!is_null($collection))
            if(is_iterable($collection))
                return $query->has('media', '>=', count($collection))->whereHas('media', function($q) use ($collection) {
                    $q->whereIn('collection_name', preg_filter('/^/', $this->getTable() . '/', $collection));
                });
            else
                return $query->whereHas('media', function($q) use ($collection){
                    $q->where('collection_name', $this->getTable().'/'.$collection);
                });
        return $query->has('media');
    }

    public function getMediasTransformation() : array
    {
        $collections = $this->getRegisteredMediaCollections();

        $response = [];
        foreach($collections as $collection) {
            $collectionName = substr($collection->name, strlen($this->getTable()) + 1); // for /
            if($this->getUploadedMedia($collectionName)){
                $media = $this->getUploadedMedia($collectionName);
                if($collectionName == 'covers' || $collectionName == 'avatars'){
                    $response[$collectionName] = [
                        'url' => $media->getUrl(),
                        'width' => (int) $media->getCustomProperty('width'),
                        'height' => (int) $media->getCustomProperty('height'),
                        'conversion' => $media->getUrl('resized'),
                        'conversion_width' => $media->model::RESIZED_WIDTH,
                        'conversion_height' => $media->model::RESIZED_HEIGHT,
                    ];
                } elseif($collectionName == 'audio' || $collectionName == 'extracts' || $collectionName == 'audios') {
                    $response[$collectionName] = [
                        'url' => base64_encode($media->getTemporaryUrl(New \DateTime('+ '. 120 .' seconds'))),
                        'size' => $media->size,
                    ];
                } elseif($collectionName == 'videos'){
                    $response[$collectionName] = [
                        'url' => base64_encode($media->getTemporaryUrl(New \DateTime('+ '. 120 .' seconds'))),
                    ];
                }
            }
        }
        return $response;
    }
}

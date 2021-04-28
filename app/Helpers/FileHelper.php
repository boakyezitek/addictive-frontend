<?php


namespace App\Helpers;


use Illuminate\Http\UploadedFile;

class FileHelper
{

    public static function urlToUploadedFile($url) : UploadedFile
    {
        try {
            $info = pathinfo($url);
            $contents = file_get_contents($url);
            $file = '/tmp/' . $info['basename'].'.jpg';
            file_put_contents($file, $contents);
            return new UploadedFile($file, $info['basename']);
        } catch (Exception $e) {
            dump($e->getMessage());
        }
    }
}

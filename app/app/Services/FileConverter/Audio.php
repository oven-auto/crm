<?php

namespace App\Services\FileConverter;

use Illuminate\Support\Facades\Storage;

Class Audio
{
    private const BASE_PATH = '/var/www/storage/app/public';



    public function convertWavToMp3(String $fileLink)
    {
        if(!Storage::disk('public')->exists($fileLink))
            return false;

        $file = self::BASE_PATH.$fileLink;

        $newFile = self::BASE_PATH.substr($fileLink, 0, \strrpos($fileLink,'.')).'.mp3';

        $res = exec('ffmpeg -i '.$file.' '.$newFile, $out, $resout);

        $result = substr($fileLink, 0, \strrpos($fileLink,'.')).'.mp3';

        Storage::disk('public')->delete($fileLink);

        return $result;
    }



    public static function wavToMp3(String $fileLink)
    {
        $audio = new Audio();

        $result = $audio->convertWavToMp3($fileLink);

        return $result;
    }
}

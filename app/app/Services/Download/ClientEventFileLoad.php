<?php

namespace App\Services\Download;

use Illuminate\Http\UploadedFile;

Class ClientEventFileLoad extends DownloadImage
{
    private static $inc = 0;
    public static function download($clientId, UploadedFile $file)
    {
        $service = new ClientEventFileLoad();
        $service->setPathName($clientId);
        $service->setPrefix($file->getClientOriginalName());
        $service->setFile($file);
        $service->setCatalog('events');
        return $service->load(true);
    }
}

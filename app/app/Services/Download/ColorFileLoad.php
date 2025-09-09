<?php

namespace App\Services\Download;

use Illuminate\Http\UploadedFile;

Class ColorFileLoad extends DownloadImage
{
    public function download(int $colorId, UploadedFile $file)
    {
        $this->setPathName($colorId);
        $this->setPrefix($colorId.'_color');
        $this->setFile($file);
        $this->setCatalog('colors');
        return $this->load(true);
    }
}
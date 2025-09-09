<?php

namespace App\Services\Download;

use Illuminate\Http\UploadedFile;

Class WorksheetFileLoad extends DownloadImage
{
    public function download(int $worksheetId, UploadedFile $file)
    {
        $this->setPathName($worksheetId);
        $this->setPrefix('');
        $this->setFile($file);
        $this->setCatalog('ws');
        return $this->load(true);
    }
}

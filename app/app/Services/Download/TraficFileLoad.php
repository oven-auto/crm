<?php

namespace App\Services\Download;

use Illuminate\Http\UploadedFile;

Class TraficFileLoad extends DownloadImage
{
    public function download(int $traficId, UploadedFile $file)
    {
        $this->setPathName($traficId);
        $this->setPrefix($traficId.'_trafic_record');
        $this->setFile($file);
        $this->setCatalog('trafic');
        return $this->load(true);
    }
}

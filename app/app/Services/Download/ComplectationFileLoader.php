<?php

namespace App\Services\Download;

use Illuminate\Http\UploadedFile;

Class ComplectationFileLoader extends DownloadImage
{
    public function download(\App\Models\Complectation $complectation, UploadedFile $file)
    {
        $this->setPathName($complectation->id);
        $this->setFile($file);
        $this->setCatalog('car/complectation');
        return $this->load(false);
    }
}

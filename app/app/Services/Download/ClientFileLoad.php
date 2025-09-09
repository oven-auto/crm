<?php

namespace App\Services\Download;

use Illuminate\Http\UploadedFile;

Class ClientFileLoad extends DownloadImage
{
    public function download($clientId, UploadedFile $file)
    {
        $this->setPathName($clientId);
        $this->setPrefix($file->getClientOriginalName().'_client_file');
        $this->setFile($file);
        $this->setCatalog('clients');
        return $this->load(true);
    }
}

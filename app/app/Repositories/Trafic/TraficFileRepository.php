<?php

namespace App\Repositories\Trafic;

use App\Models\Trafic;
use App\Models\TraficFile;
use App\Services\Trafic\SaveTraficFile;

Class TraficFileRepository
{
    public function get(Trafic|int $trafic)
    {
        if(is_numeric($trafic))
            $trafic = Trafic::find($trafic);

        return $trafic->files;
    }



    public function saveTraficFiles(Trafic $trafic, $files)
    {
        $now = now()->subSecond();
        
        SaveTraficFile::save($trafic, $files);

        return $trafic->files->where('created_at', '>', $now)->values();
    }



    public function delete(TraficFile $file)
    {
        $file->delete();
    }
}

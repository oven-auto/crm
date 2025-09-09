<?php

namespace App\Services\Trafic;

use App\Models\Trafic;
use App\Services\Download\TraficFileLoad;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

Class SaveTraficFile extends AbstractTraficSaveService
{
    public $loadService;

    public function __construct()
    {
        $this->loadService = new TraficFileLoad();
    }



    public function action(Trafic $trafic, array $data)
    {
        if(!count($data))
            return;

        foreach($data as $itemFile) 
            if($itemFile instanceof UploadedFile)
                $trafic->files()->create([
                    'name' => $itemFile->getClientOriginalName(),
                    'filepath' => $this->loadService->download($trafic->id, $itemFile),
                    'user_id' => Auth::id()
                ]);
    }
}
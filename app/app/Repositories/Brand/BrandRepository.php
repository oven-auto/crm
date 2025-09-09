<?php

namespace App\Repositories\Brand;

use App\Models\Brand;
use App\Services\Download\DownloadImage;
use Illuminate\Http\UploadedFile;

Class BrandRepository
{
    private $loadService;



    public function __construct(DownloadImage $loadService)
    {
        $this->loadService = $loadService;
    }



    public function getAll()
    {
        return Brand::get();
    }



    public function save(Brand $brand, $data = []) :Brand
    {
        if (isset($data['icon']) && $data['icon'] instanceof UploadedFile)
            $data['icon'] = $this->loadService
                ->setFile($data['icon'])
                ->setCatalog('brand')
                ->setPrefix('brand')
                ->save();
        $brand->fill($data);
        $brand->save();
        return $brand;
    }
}

<?php

namespace App\Repositories;

use App\Models\Banner;
use Storage;
use Illuminate\Http\UploadedFile;

Class BannerRepository
{
    public function save(Banner $banner, $data = [])
    {
        $result = [];
        try {
            if (isset($data['image']) && $data['image'] instanceof UploadedFile)
                $data['image'] = $this->saveImage($data['image']);
            else
                unset($data['image']);

            $banner->fill($data);
            if(!$banner->id)
                $banner->sort = Banner::max('sort')+1;;
            $banner->save();
            $result = ['status' => 1];
        } catch(\Exception $e) {
            $result = ['status'=>0,'error'=>$e->getMessage()];
        }
        return $result;
    }

    public function saveImage($file)
    {
        $bannerName = 'banner_'.date('dmyhms').'.'.$file->getClientOriginalExtension();
        $path = '/public/banner/';
        $urlPath = '/banner/';
        $finalName = $urlPath.'/'.$file->move(Storage::path($path), $bannerName)->getFilename();
        return $finalName;
    }
}

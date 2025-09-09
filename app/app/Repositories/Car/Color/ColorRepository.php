<?php

namespace App\Repositories\Car\Color;

use App\Http\DTO\Car\Color\CreateColorDTO;
use App\Http\Filters\DealerColorFilter;
use App\Models\DealerColor;
use App\Models\DealerColorImage;
use App\Repositories\Car\Color\DTO\ColorDTO;
use App\Services\Download\ColorFileLoad;
use \Illuminate\Database\Eloquent\Collection;

Class ColorRepository
{
    public function getById(int $id) : DealerColor
    {
        $dealerColor = DealerColor::findOrFail($id);

        return $dealerColor;
    }



    public function store(CreateColorDTO $data) : DealerColor
    {
        $color = DealerColor::create(array_merge(
            (new ColorDTO((array) $data))->get(),
            ['author_id' => auth()->user()->id],
        ));

        return $color;
    }



    public function update(DealerColor $dealerColor, CreateColorDTO $data) : DealerColor
    {
        $dealerColor->fill(array_merge(
            (new ColorDTO((array) $data))->get(),
            ['author_id' => auth()->user()->id],
        ))->save();

        return $dealerColor;
    }



    public function delete(DealerColor $dealerColor) : void
    {
        $dealerColor->delete();
    }



    public function restore(DealerColor $dealerColor) : void
    {
        $dealerColor->restore();
    }



    public function get(array $data) : Collection
    {
        $query = DealerColor::select('dealer_colors.*')->with(['mark', 'brand', 'base', 'author']);

        $filter = app()->make(DealerColorFilter::class, ['queryParams' => $data]);
        
        $query->filter($filter);

        $colors = $query->get();

        return $colors;
    }



    public function list(int $markId) : Collection
    {
        $query = DealerColor::select('dealer_colors.id', 'dealer_colors.name');

        $query->where('mark_id', $markId);

        $list = $query->get();

        return $list;
    }



    public function getColorImages(array $data)
    {
        $query = DealerColorImage::query();

        if(isset($data['color_id']))
            $query->where('dealer_color_id', $data['color_id']);

        $images = $query->get();

        return $images;
    }



    public function appendImage(array $data)
    {
        $service = new ColorFileLoad();

        $strImage = $service->download($data['color_id'], $data['image']);
        
        $image = DealerColorImage::create([
            'image' => $strImage,
            'body_work_id' => $data['bodywork'],
            'dealer_color_id' => $data['color_id'],
        ]);

        return $image;
    }



    public function updateImage(DealerColorImage $image, array $data)
    {
        $image->body_work_id = $data['bodywork'];
        
        if(isset($data['image']))
        {
            unset($image->image);
            
            $service = new ColorFileLoad();

            $strImage = $service->download($image->dealer_color_id, $data['image']);

            $image->image = $strImage;
        }

        $image->save();

        return $image;
    }



    public function deleteImage(DealerColorImage $image)
    {
        unset($image->image);

        $image->delete();
    }
}

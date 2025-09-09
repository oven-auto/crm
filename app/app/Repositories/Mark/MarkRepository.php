<?php

namespace App\Repositories\Mark;

use App\Models\Mark;
use Illuminate\Http\UploadedFile;
use DB;
use App\Services\Download\DownloadImage;
use Illuminate\Support\Arr;

Class MarkRepository
{
    const MARK_COL = [
        'name', 'prefix', 'sort', 'status', 'brand_id', 'body_work_id', 'country_factory_id', 'show_toxic', 'show_driver'
    ];

    private $loadService;

    public function __construct(DownloadImage $service)
    {
        $this->loadService = $service;
    }

    public function getAll($data = [])
    {
        $query = Mark::select('marks.*')->with(['icon', 'bodywork', 'brand']);

        if(isset($data['brand_id']))
            $query->where('brand_id', $data['brand_id']);

        $marks = $query->leftJoin('brands','brands.id','marks.brand_id')
            ->where('diller_status', 1)
            ->where('brands.diller',1)
            ->orderBy('status','DESC')
            ->orderBy('sort')
            ->get();

        return $marks;
    }

    public function saveMark(Mark $mark, $data = []) :void
    {
        try {
            DB::transaction(function () use ($mark, $data) {
                $this->saveMain($mark, Arr::only($data, self::MARK_COL));

                if (isset($data['info']))
                    $this->saveInfo($mark, $data['info']);

                if (isset($data['properties']))
                    $this->saveProperties($mark, $data['properties']);

                if (isset($data['icon']))
                    $this->saveIcon($mark, $data['icon']);
                else
                    $mark->icon()->create(['image' => '/mark/some/somecar.png']);

                if (isset($data['banner']))
                    $this->saveBanner($mark, $data['banner']);
                else
                    $mark->banner()->create(['image' => '/mark/some/somecar.png']);

                if (isset($data['document']))
                    $this->saveDocuments($mark, $data['document']);

                if (isset($data['colors']))
                    $this->saveColors($mark, $data['colors']);

            });
        } catch(\Exception $e) {
			die($e);
		}
    }

    private function saveMain(Mark $mark, $data = []) :void
    {
        $mark->fill($data);
        if(!$mark->id)
            $mark->sort = Mark::max('sort')+1;
        $mark->save();
    }

    private function saveInfo(Mark $mark, $data = []) :void
    {
        $mark->info->fill($data)->save();
    }

    private function saveProperties(Mark $model, $data = []) :void
    {
        $model->properties()->detach();
        foreach($data as $id => $value)
            $model->properties()->attach($id, ['value' => $value]);
    }

    private function saveIcon(Mark $mark, $file) :void
    {
        if ($file instanceof UploadedFile){
            $arr['image'] = $this->loadService
                ->setFile($file)
                ->setCatalog('mark')
                ->setPathName($mark->slug)
                ->setPrefix($mark->slug.'_icon')
                ->save();
            $mark->icon->fill($arr)->save();
        }
    }

    private function saveBanner(Mark $mark, $file) :void
    {
        if ($file instanceof UploadedFile){
            $arr['image'] = $this->loadService
                ->setFile($file)
                ->setCatalog('mark')
                ->setPathName($mark->slug)
                ->setPrefix($mark->slug.'_banner')
                ->save();
            $mark->banner->fill($arr)->save();
        }
    }

    private function saveDocuments(Mark $mark, $data = [], $arr = []) :void
    {
        foreach($data as $key => $item)
            if ($item instanceof UploadedFile)
                $arr[$key] = $this->loadService
                    ->setFile($item)
                    ->setCatalog('mark')
                    ->setPathName($mark->slug.'/documents')
                    ->setPrefix($mark->slug.'_'.$key)
                    ->save();
        $mark->document->fill($arr)->save();
    }

    private function saveColors(Mark $mark, $data = [],$idArray = [])
    {
        foreach($data as $colorId => $item) {
            $idArray[] = $colorId;
            if ($item instanceof UploadedFile) {
                $arr['color_id'] = $colorId;
                $arr['image'] = $this->loadService
                    ->setFile($item)
                    ->setCatalog('mark')
                    ->setPathName($mark->slug.'/colors')
                    ->setPrefix($mark->slug.'_'.$colorId)
                    ->save();
                if($mark->markcolors->contains('color_id', $colorId))
                    $mark->markcolors->where('color_id', $colorId)->first()->fill($arr)->save();
                else $mark->markcolors->create($arr);
            } elseif($item == '') {
                $mark->markcolors()->create([
                    'color_id' => $colorId,
                    'image' => '/mark/some/somecar.png'
                ]);
            }
        }
        $mark->markcolors()->whereNotIn('color_id', $idArray)->delete();
    }

    public function getMarkTabList()
    {
        $marks = Mark::select('name', 'prefix', 'id', 'body_work_id','slug')
            ->with(['icon','bodywork','basecomplectation'])
            ->get();

        return $marks;
    }

    public function getMarkBySlug($slug)
    {
        $mark = Mark::with(['bodywork','banner', 'brand', 'info', 'properties','markcolors'])
            ->where('slug',$slug)
            ->first();

        return $mark;
    }

    public function getMarksName()
    {
        $marks = Mark::select('name','prefix','id', 'slug')->get();
        return $marks;
    }
}

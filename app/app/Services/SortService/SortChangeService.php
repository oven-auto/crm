<?php

namespace App\Services\SortService;

use App\Models\Interfaces\SortInterface;

/*
    Класс для изменения сортировки,
*/
Class SortChangeService {
    /*
        Поменять местами 2 сущности, не трогая остальные. Те если сущность А имела порядок 2,
        а сущность С имела порядок 10, то после выполнения этого метода, А станет 10, С станет 2
    */
    public function swapPlace(SortInterface $model, $data = [])
    {
        $activeComplectation = $model::find($data['active']['id']);
        $secondComplectation = $model::find($data['second']['id']);

        $sortOld = $activeComplectation->sort;
        $sortNew = $secondComplectation->sort;

        $activeComplectation->sort = $sortNew;
        $secondComplectation->sort = $sortOld;

        $activeComplectation->save();
        $secondComplectation->save();

        $result = [
            $activeComplectation->id=>$activeComplectation->sort,
            $secondComplectation->id=>$secondComplectation->sort
        ];

        return $result;
    }

    /*
        Изменить порядок искомой сущности, вставив ее перед другой сущностью, тем самым изменив порядок множества сущностей
    */
    public function changeSort(SortInterface $model, $data = [])
    {

        $previos = $model::find($data['active']['id']);

        $original = $model::find($data['second']['id']);

        $newSort = $previos->sort;
        $originalSort = $original->sort;

        if($originalSort < $newSort) {
            $sortMas = $model->where('sort','>',$originalSort)->where('sort','<=',$newSort)->get();
            foreach($sortMas as $mas) {
                $mas->sort -=1;
                $mas->save();
            }
        }

        elseif($originalSort > $newSort) {
            $sortMas = $model->where('sort','>=',$newSort)->where('sort','<',$originalSort)->get();
            foreach($sortMas as $mas) {
                $mas->sort +=1;
                $mas->save();
            }
        }

        $original->sort = $newSort;
        $original->save();
    }
}

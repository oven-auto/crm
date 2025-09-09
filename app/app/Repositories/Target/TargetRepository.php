<?php

namespace App\Repositories\Target;

use App\Helpers\Date\DateHelper;
use App\Models\Target;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

Class TargetRepository
{
    public function paginate(array $data)
    {
        $res =  Target::with(['marks', 'author', 'brand'])->orderBy('date_at', 'desc')->simplePaginate(50);

        return $res;
    }



    public function getById(int $id)
    {
        return Target::with('marks')->findOrFail($id);
    }



    public function canCreate(int $brand, Carbon $date)
    {
        $searched = Target::select('id')
            ->where('brand_id', $brand)
            ->whereRaw('YEAR(date_at) = '.$date->year)
            ->whereRaw('MONTH(date_at) = '.$date->month)
            ->first();

        if($searched)
            return 0;
        return 1;
    }



    public function store(array $data)
    {
        $targetData = Arr::except(array_merge($data, ['author_id' => Auth::id()]), ['marks']);

        $targetData['date_at'] = DateHelper::createFromString($targetData['date_at'], 'm.Y');
        
        if(!$this->canCreate($targetData['brand_id'], $targetData['date_at']))
            throw new Exception('Уже имеется план на этот период, по этому бренду.');

        $markData = [];

        foreach($data['marks'] as $item)
            $markData[$item['id']] = ['amount' => $item['amount']];
        
        $target = Target::create($targetData);

        $target->marks()->sync($markData);

        $target->load('marks');

        return $target;
    }



    public function update(int $id, array $data)
    {
        $target = $this->getById($id);

        $targetData = Arr::except(array_merge($data, ['author_id' => Auth::id()]), ['marks']);
        
        $targetData['date_at'] = DateHelper::createFromString($targetData['date_at'], 'm.Y');

        $markData = [];

        foreach($data['marks'] as $item)
            $markData[$item['id']] = ['amount' => $item['amount']];
        
        $target->fill($targetData);

        $target->save();

        $target->marks()->sync($markData);

        $target->load('marks');

        return $target;
    }



    public function delete(int $id)
    {
        $target = $this->getById($id);

        $target->delete();
    }



    public function count()
    {
        
    }
}
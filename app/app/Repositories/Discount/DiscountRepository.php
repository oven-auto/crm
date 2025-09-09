<?php

namespace App\Repositories\Discount;

use App\Http\Filters\DiscountFilter;
use App\Models\Discount;
use App\Models\DiscountType;
use App\Repositories\Discount\DTO\DiscountCarDTO;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DiscountRepository
{
    public function all(array $data)
    {
        $query = DiscountType::query();

        if (isset($data['trash']) && $data['trash'] == 1)
            $query->onlyTrashed();

        if (isset($data['all']) && $data['all'] == 1)
            $query->withTrashed();

        if (isset($data['modul_id']))
            $query->where('modul_id', $data['modul_id']);

        $discounts = $query->get();

        return $discounts;
    }



    public function save(DiscountType $discount, array $data)
    {
        if($discount->exported)
            throw new \Exception('Это системный вид скидок. Редактирование запрещено.');
        
        $dto = (new DiscountCarDTO($data))->get();

        $discount->author_id = auth()->user()->id;

        $discount->fill($dto)->save();
    }



    public function delete(DiscountType $discount)
    {
        $discount->delete();
    }



    public function restore(DiscountType $discount)
    {
        $discount->restore();
    }



    public function paginate(array $data, $paginate = 20)
    {   
        $query = Discount::select('discounts.*');

        $filter = app()->make(DiscountFilter::class, ['queryParams' => array_filter($data)]);
        
        $query->filter($filter)->orderBy('discounts.worksheet_id', 'DESC');

        $discounts = $query->simplePaginate($paginate);

        return $discounts;
    }



    public function count(array $data = []) : mixed
    {
        $query = Discount::query()->select(
            DB::raw('count(*) as total'),
            DB::raw('sum(discount_sums.amount) as sum'),
            DB::raw('sum(discount_reparations.amount) as reparation')
        );

        $filter = app()->make(DiscountFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $countCars = DB::table($query)->get();

        return $countCars;
    }
}

<?php

namespace App\Repositories\Car\Complectation;

use App\Http\Filters\ComplectationFilter;
use App\Models\Complectation;
use App\Models\ComplectationFile;
use App\Models\ComplectationPrice;
use App\Models\Motor;
use App\Repositories\Car\Complectation\DTO\ComplectationDTO;
use App\Repositories\Car\Complectation\DTO\MotorDTO;
use App\Services\Download\ComplectationFileLoader;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ComplectationRepository
{
    private $loader;

    public function __construct(ComplectationFileLoader $loader)
    {
        $this->loader = $loader;
    }



    /**
     * SAVE HISTORY
     * @param Complectation $complectation
     * @param string $message
     * @return void
     */
    public function saveHistory(Complectation $complectation, string $message): void
    {
        $complectation->history()->create([
            'author_id' => auth()->user()->id,
            'comment' => $message,
        ]);
    }



    /**
     * LOAD FILE
     * @param Complectation $complectation
     * @param mixed $file - хз может че поменяется
     * @return void
     */
    public function loadFile(Complectation $complectation, mixed $file): void
    {
        if (ComplectationFileLoader::isFile($file)) {
            $name = $this->loader->download($complectation, $file);
            $complectation->file()->updateOrCreate(
                ['complectation_id' => $complectation->id],
                ['author_id' => auth()->user()->id, 'file' => $name]
            );
        }
    }



    private function save(Complectation $complectation, array $data)
    {
        $complectation->fill((new ComplectationDTO($data))->get())->save();

        $complectation->saveAlias($data['alias_id']);
    }



    /**
     * CREATE COMPLECTATION
     * @param array $data
     * @return Complectation
     */
    public function create(array $data): Complectation
    {
        $motor = Motor::create((new MotorDTO($data))->get());

        $data['motor_id'] = $motor->id;

        $complectation = new Complectation();

        $data['author_id'] = auth()->user()->id;

        $this->save($complectation, $data);

        $this->saveHistory($complectation, 'create');

        !isset($data['file']) ?: $this->loadFile($complectation, $data['file']);

        return $complectation;
    }



    /**
     * UPDATE
     * @param Complectation $complectation
     * @param array $data
     * @return void
     */
    public function update(Complectation $complectation, array $data): void
    {
        $complectation->motor->fill((new MotorDTO($data))->get())->save();

        $this->save($complectation, $data);

        $this->saveHistory($complectation, 'update');

        !isset($data['file']) ?: $this->loadFile($complectation, $data['file']);
    }



    /**
     * DELETE
     * @param Complectation $complectation
     * @return void
     */
    public function delete(Complectation $complectation): void
    {
        $complectation->delete();
    }



    /**
     * RESTORE
     * @param Complectation $complectation
     * @return void
     */
    public function restore(Complectation $complectation): void
    {
        $complectation->restore();
    }



    /**
     * GET COMPLECTATIONS BY PARAM
     * @param array $data [mark_id | trash]
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(array $data): \Illuminate\Database\Eloquent\Collection
    {
        $query = Complectation::select('complectations.*')
            ->with(['mark', 'motor', 'vehicle', 'factory', 'bodywork']);

        if (isset($data['mark_id']))
            $query->where('complectations.mark_id', $data['mark_id']);

        if (isset($data['trash']) && $data['trash'])
            $query->onlyTrashed();

        if (isset($data['code']) && $data['code'])
            $query->where('complectations.code', 'LIKE', '%' . $data['code'] . '%');

        $complectations = $query->get();

        return $complectations;
    }



    /**
     * SEARCH BY CODE
     * @param string $code
     * @return Complectation | null
     */
    public function searchByCode(array $data): Complectation | null
    {
        $complectation = Complectation::select('complectations.*')
            ->withTrashed()
            ->where('complectations.code', $data['code'])
            ->where('complectations.mark_id', $data['mark_id'])
            ->first();

        return $complectation;
    }



    public function list(array $data, $paginate = 20)
    {
        $query = Complectation::query()
            ->fullRelations();

        $filter = app()->make(ComplectationFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);
        
        $complectations = $query
            ->orderBy('complectations.deleted_at')
            ->orderBy('marks.brand_id')
            ->orderBy('marks.id')
            ->orderBy('complectations.body_work_id')
            ->get();
        
        return $complectations;
    }



    public function count(array $data)
    {
        $query = Complectation::query()->select('complectations.id');

        $filter = app()->make(ComplectationFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $countCars = DB::table($query)->count();

        return $countCars;
    }



    /**
     * Получить дату самой последней даты цены комплектации dd.mm.YYYY
     * @return string
     */
    public function maxComplectationDatePrice()
    {
        $date = (new Carbon(ComplectationPrice::select(DB::raw('max(begin_at) as date'))->first()->date))->format('d.m.Y');

        return $date;
    }
}

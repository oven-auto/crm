<?php

namespace App\Repositories\Credit\Tactic;

use App\Http\DTO\Credit\TacticCreateDTO;
use App\Models\CreditTactic;

Class CreditTacticRepository
{
    public function getById(int $id)
    {
        return CreditTactic::withTrashed()->findOrFail($id);
    }



    public function get(array $data)
    {
        $query = CreditTactic::query();

        if(isset($data['trash']) && $data['trash'])
            $query->onlyTrashed();

        return $query->get();
    }



    public function create(TacticCreateDTO $dto)
    {
        $tactic = CreditTactic::create((array) $dto);

        return $tactic;
    }



    public function update(int $id, TacticCreateDTO $dto)
    {
        $tactic = $this->getById($id);

        $tactic->fill((array) $dto);

        if($tactic->isDirty())
            $tactic->save();

        return $tactic;
    }



    public function delete(int $id)
    {
        $tactic = $this->getById($id);

        $tactic->delete();
    }



    public function restore(int $id)
    {
        $tactic = $this->getById($id);

        $tactic->restore();
    }
}
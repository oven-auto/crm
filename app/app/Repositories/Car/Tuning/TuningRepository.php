<?php

namespace App\Repositories\Car\Tuning;

use App\Models\Tuning;

class TuningRepository
{
    public function save(Tuning $tuning, array $data)
    {
        $tuning->fill($data)->save();
    }



    public function get(array $data)
    {
        $query = Tuning::query();

        if (isset($data['trash']) && $data['trash'])
            $query->onlyTrashed();

        $tunings = $query->get();

        return $tunings;
    }



    public function destroy(Tuning $tuning)
    {
        $tuning->delete();
    }



    public function restore(Tuning $tuning)
    {
        $tuning->restore();
    }
}

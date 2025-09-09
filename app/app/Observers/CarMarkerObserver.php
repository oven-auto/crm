<?php

namespace App\Observers;

use App\Models\CarMarker;
use Auth;

class CarMarkerObserver
{
    public function saved(CarMarker $marker)
    {
        $newVal = $marker->marker_id;
        $oldVal = $marker->getOriginal('marker_id');

        $marker->refresh();

        if (Auth::check()) {
            $userId = Auth::user()->id;
            if($newVal != $oldVal) {
                $marker->user_id = $userId;
                $marker->changed_at = date('d.m.Y');
                $marker->save();
            }
        }
    }
}

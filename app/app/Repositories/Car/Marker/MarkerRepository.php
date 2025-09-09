<?php

namespace App\Repositories\Car\Marker;

use App\Repositories\Car\Marker\DTO\MarkerDTO;
use App\Models\Marker;

Class MarkerRepository
{
    /**
     * STORE
     * @param array $data ['name', 'text_color', 'body_color', 'description']
     * @return Marker
     */
    public function store(array $data) : Marker
    {
        $marker = Marker::create(array_merge((new MarkerDTO($data))->get(), ['author_id' => auth()->user()->id]));

        return $marker;
    }



    /**
     * UPDATE
     * @param Marker $marker
     * @param array $data ['name', 'text_color', 'body_color', 'description']
     * @return void
     */
    public function update(Marker $marker, array $data) : void
    {
        $marker->fill(array_merge((new MarkerDTO($data))->get(), ['author_id' => auth()->user()->id]))->save();
    }



    /**
     * DELETE
     * @param Marker $marker
     * @return void
     */
    public function delete(Marker $marker) : void
    {
        $marker->delete();
    }



    /**
     * RESTORE
     * @param Marker $marker
     * @return void
     */
    public function restore(Marker $marker) : void
    {
        $marker->restore();
    }



    /**
     * GET
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(array $data) : \Illuminate\Database\Eloquent\Collection
    {
        $query = Marker::with(['author']);

        if(isset($data['trash']))
            $query->onlyTrashed();

        $markers = $query->get();

        return $markers;
    }
}

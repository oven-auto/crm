<?php

namespace App\Repositories\Credit\Content;

use App\Http\DTO\Credit\CreditContentCreateDTO;
use App\Models\CreditContent;

Class CreditContentRepository
{
    public function getById(int $id)
    {
        return CreditContent::withTrashed()->findOrFail($id);
    }



    public function get(array $data)
    {
        $query = CreditContent::query();

        if(isset($data['trash']) && $data['trash'])
            $query->onlyTrashed();

        $contents = $query->get();

        return $contents;
    }



    public function create(CreditContentCreateDTO $dto)
    {   
        $content = CreditContent::create((array) $dto);
       
        return $content;
    }



    public function update(int $id, CreditContentCreateDTO $dto)
    {
        $content = $this->getById($id);

        $content->fill((array) $dto);

        if($content->isDirty())
            $content->save();

        return $content;
    }




    public function delete(int $id)
    {
        $content = $this->getById($id);

        $content->delete();
    }



    public function restore(int $id)
    {
        $content = $this->getById($id);

        $content->restore();

        return $content;
    }
}
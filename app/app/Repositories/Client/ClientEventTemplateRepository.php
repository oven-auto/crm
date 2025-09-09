<?php

namespace App\Repositories\Client;

use App\Http\DTO\Client\ClientEvent\ClientEventTemplateDTO;
use App\Http\Filters\ClientEventTemplateFilter;
use App\Models\ClientEventTemplate;

class ClientEventTemplateRepository
{
    public function get(array $data)
    {
        $query = ClientEventTemplate::query()->select('client_event_templates.*');

        $filter = app()->make(ClientEventTemplateFilter::class, ['queryParams' => array_filter($data)]);

        $query->filter($filter);

        $templates = $query->groupBy('client_event_templates.id')->get();

        return $templates;
    }



    public function getById(int $id)
    {
        return ClientEventTemplate::withTrashed()->findOrFail($id);
    }



    public function create(ClientEventTemplateDTO $dto)
    {
        $template = ClientEventTemplate::create((array)$dto);

        return $template;
    }



    public function update(int $id, ClientEventTemplateDTO $dto)
    {
        $template = $this->getById($id);

        $template->fill((array)$dto)->save();

        return $template;
    }



    public function delete(int $id)
    {
        $template = $this->getById($id);

        $old = $template->replicate();

        $template->delete();
        
        return $old;
    }



    public function restore(int $id)
    {
        $template = $this->getById($id);

        $template->restore();

        return $template;
    }
}
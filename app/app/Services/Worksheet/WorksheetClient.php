<?php

namespace App\Services\Worksheet;
use App\Models\Client;
use App\Models\WorksheetSubClient;
use App\Repositories\Client\ClientUnionRepository;
use App\Models\Worksheet;
use App\Services\Comment\Comment;

/**
 * Класс для добавления/удаления контактных лиц из РЛ
 */
Class WorksheetClient
{
    /**
     * Добавить контактное лицо в РЛ
     * @param Worksheet $worksheet РЛ в который хотим добавить контактное лицо
     * @param Client $client Клиент которого хотим сделать контактным лицом
     */
    public static function attach(Worksheet $worksheet, Client $client, $comment = 0) : void
    {
        if($worksheet->subclients->contains('id', $client->id))
            throw new \Exception('Клиент уже добавлен');

        $worksheet->subclients()->attach($client->id);

        $clientUnionRepository = new ClientUnionRepository();

        $clientUnionRepository->addUnion($worksheet->client, $client->id);

        $worksheetSubClient = new WorksheetSubClient();

        $worksheetSubClient->fill(['worksheet_id' => $worksheet->id, 'client_id' => $client->id]);

        if($comment == 0)
            Comment::add($worksheetSubClient, 'attach');
    }

    /**
     * Удалить контактное лицо в РЛ
     * @param Worksheet $worksheet РЛ в который хотим добавить контактное лицо
     * @param Client $client Клиент которого хотим сделать контактным лицом
     */
    public static function detach(Worksheet $worksheet, Client $client, $comment = 0) : void
    {
        $worksheetSubClient = new WorksheetSubClient();

        $worksheetSubClient->fill(['worksheet_id' => $worksheet->id, 'client_id' => $client->id]);

        if($comment == 0)
            Comment::add($worksheetSubClient, 'detach');

        $worksheet->subclients()->detach($client->id);
    }

    /**
     * Заменить клиента РЛ, на контактное лицо
     * @param Worksheet $worksheet РЛ в котором хотим заменить клиента
     * @param Client $client Новый клиент
     */
    public static function change(Worksheet $worksheet, Client $newClient)
    {
        $oldClient = Client::find($worksheet->client_id);

        Comment::add($worksheet->last_action, 'delete_client');

        $worksheet->fill(['client_id' => $newClient->id])->save();

        WorksheetClient::attach($worksheet, $oldClient, 1);
        WorksheetClient::detach($worksheet, $newClient, 1);

        Comment::add($worksheet->last_action, 'set_client');

        $worksheet->load('subclients');

        return $worksheet;
    }
}

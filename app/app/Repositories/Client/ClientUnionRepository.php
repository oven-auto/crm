<?php

namespace App\Repositories\Client;
use App\Models\Client;
use App\Services\Comment\Comment;
use App\Models\ClientUnion;

class ClientUnionRepository
{
    /**
     * Получить всех связанных с клиентом клиентов (как прямых , так и обратных)
     * @param Client $client App\Models\Client
     * @return \Illuminate\Support\Collection
     */
    public function getAllUnion(Client $client) : \Illuminate\Support\Collection
    {
        return $client->unionsChildren->merge($client->unionsParent);
    }

    /**
     * Добавить связь для клиента
     * @param Client $client App\Models\Client
     * @param integer $unionClientId идентификатор клиента которого хотим привязать
     * @return void
     */
    public function addUnion(Client $client, int $unionClientId) : void
    {
        // if($unionClientId == $client->id)
        //     throw new \Exception('Клиент к которму Вы хотите добавить связь не может быть тем же кого добавляете');

        $collect = $client->unionsParent->merge($client->unionsChildren);

        // if($collect->contains('id', $unionClientId))
        //     throw new \Exception('Связь уже есть');
        if(!$collect->contains('id', $unionClientId))
        {
            $unions = $client->unionsChildren->pluck('id')->toArray();
            array_push($unions, $unionClientId);
            $unions = array_unique($unions);

            $client->unionsChildren()->sync($unions);

            $client = $client->fresh();
        }
        Comment::add(ClientUnion::fillData($client->id, $unionClientId), 'create');
    }

    /**
     * Удалить связь для клиента
     * @param Client $client App\Models\Client
     * @param integer $unionClientId идентификатор клиента которого хотим отвязать
     * @return void
     */
    public function delUnion(Client $client, int $unionClientId) : void
    {
        $client->unionsChildren()->detach($unionClientId);
        $client->unionsParent()->detach($unionClientId);

        Comment::add(ClientUnion::fillData($client->id, $unionClientId), 'delete');
    }

    /**
     * Получить кол-во связей у клиента с определенным id
     * @param integer $client
     * @return integer
     */
    public function countUnion($client) : int
    {
        $count = ClientUnion::where('client_id', $client)->orWhere('parent', $client)->count();
        return $count;
    }
}

<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientLink;
use App\Services\GetShortCutFromURL\GetShortCutFromURL;
use Illuminate\Http\Request;
use App\Services\Comment\Comment;

class ClientLinkController extends Controller
{
    public function index(Client $client, Request $request)
    {
        return response()->json([
            'data' => $client->links->map(fn($item) => [
                'id'            => $item->id,
                'text'          => $item->url,
                'author'        => $item->author->cut_name,
                'created_at'    => $item->created_at->format('d.m.Y (H:i)'),
                'icon'          => $item->icon,
            ]),
            'success' => 1,
        ]);
    }



    public function store(Client $client, Request $request)
    {
        $obj = ClientLink::create([
            'client_id' => $client->id,
            'author_id' => auth()->user()->id,
            'icon' => GetShortCutFromURL::get($request->url),
            'url' => $request->url,
        ]);

        Comment::add($obj, 'create');

        return response()->json([
            'data' => [
                'id'            => $obj->id,
                'text'          => $obj->url,
                'author'        => $obj->author->cut_name,
                'created_at'    => $obj->created_at->format('d.m.Y (H:i)'),
                'icon'          => $obj->icon,
            ],
            'success' => 1,
            'message' => 'Ссылка добавлена',
        ]);
    }


    
    public function delete(ClientLink $clientlink)
    {
        Comment::add($clientlink, 'delete');

        $clientlink->delete();

        return response()->json([
            'success' => 1,
            'message' => 'Ссылка удалена'
        ]);
    }
}

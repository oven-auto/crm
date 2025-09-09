<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientEventFile;
use Illuminate\Http\Request;

class ClientEventFileDeleteController extends Controller
{
    public function __invoke(ClientEventFile $clientEventFile)
    {
        $clientEventFile->delete();
        return response()->json([
            'message' => 'Фаил удален',
            'success' => 1
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *  title="API",
 *  version="1.0"
 * )
 * @OA\Tag(
 *  name="Get",
 *  description="Get methods"
 * )
 * @OA\Server(
 *  description="Сервер",
 *  url="http://192.168.1.98:8280/api"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

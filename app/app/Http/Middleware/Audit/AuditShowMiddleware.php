<?php

namespace App\Http\Middleware\Audit;

use App\Models\Audit\AuditMaster;
use App\Repositories\Audit\AuditMasterRepository;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditShowMiddleware
{
    public function __construct(
        private AuditMasterRepository $repo
    )
    {
        
    }



    public function handle(Request $request, Closure $next): Response
    {
        if(!isset($request->master))
            throw new Exception('Идентификатор не указан.');
        
        $audit = $this->repo->getById($request->master);

        if(!$audit)
            throw new Exception(AuditMaster::EXCEPTIONS['find_fail']); 

        return $next($request);
    }
}

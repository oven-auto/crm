<?php

namespace App\Http\Middleware\Audit;

use App\Models\Audit\AuditMaster;
use App\Repositories\Audit\AuditMasterRepository;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditDeleteMiddleware
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

        if($audit->isClose())
            throw new Exception(AuditMaster::EXCEPTIONS['delete_close']);

        if($audit->isWait())
            throw new Exception(AuditMaster::EXCEPTIONS['delete_wait']);

        if($audit->isDeleted())
            throw new Exception(AuditMaster::EXCEPTIONS['delete_only_job']);

        return $next($request);
    }
}

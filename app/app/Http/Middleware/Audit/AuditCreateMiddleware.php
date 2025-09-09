<?php

namespace App\Http\Middleware\Audit;

use App\Models\Audit\AuditMaster;
use App\Repositories\Audit\AuditMasterRepository;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditCreateMiddleware
{
    public function __construct(
        private AuditMasterRepository $repo
    )
    {
        
    }


    
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->has('trafic_id') || !$request->has('audit_id'))
            throw new Exception('Идентификатор трафика или аудита не указан.');

        if($this->repo->isExist($request->only(['trafic_id','audit_id'])))
            throw new Exception(AuditMaster::EXCEPTIONS['already_exist']);

        return $next($request);
    }
}

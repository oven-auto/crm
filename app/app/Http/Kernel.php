<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustHosts::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:60000,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\BlockMiddleware::class,
            \App\Http\Middleware\System\SetMacrotimeMiddleware::class,
            \App\Http\Middleware\System\EndMacrotimeMiddleware::class,
        ],

        'export' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:60000,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        '1c' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:60000,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\For1c\CheckMiddleware::class,
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'block' => \App\Http\Middleware\BlockMiddleware::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'userfromtoken' => \App\Http\Middleware\UserWithTokenMiddleware::class,
        'corsing' => \App\Http\Middleware\CorsMiddleware::class,
        'robot' => \App\Http\Middleware\RobotMiddleware::class,
        'notice.message' => \App\Http\Middleware\Notice\NoticeMessageMiddleware::class,
        'worksheet.create.base' => \App\Http\Middleware\Permissions\Worksheet\WorksheetBasePerm::class,
        'permission.trafic.list' => \App\Http\Middleware\Permissions\Trafic\TraficList::class,
        'permission.trafic.create' => \App\Http\Middleware\Permissions\Trafic\TraficCreate::class,
        'permission.trafic.show' => \App\Http\Middleware\Permissions\Trafic\TraficShow::class,
        'permission.trafic.showalien' => \App\Http\Middleware\Permissions\Trafic\TraficShowAlien::class,
        'permission.trafic.showdraft' => \App\Http\Middleware\Permissions\Trafic\CanShowDraft::class,
        'permission.trafic.delete' => \App\Http\Middleware\Permissions\Trafic\TraficDelete::class,
        'permission.trafic.close' => \App\Http\Middleware\Permissions\Trafic\TraficClose::class,
        'permission.worksheet.create' => \App\Http\Middleware\Permissions\Worksheet\WorksheetCreate::class,
        'permission.worksheet.show'   => \App\Http\Middleware\Permissions\Worksheet\WorksheetShow::class,
        'permission.worksheet.close'  => \App\Http\Middleware\Permissions\Worksheet\WorksheetClose::class,
        'permission.worksheet.list'  => \App\Http\Middleware\Permissions\Worksheet\WorksheetList::class,
        'permission.worksheet.revert'  => \App\Http\Middleware\Permissions\Worksheet\WorksheetRevert::class,
        'permission.worksheet.action' => \App\Http\Middleware\Permissions\Worksheet\WorksheetActionMiddleware::class,
        'permission.worksheet.complete' => \App\Http\Middleware\Permissions\Worksheet\Action\ActionCompleteMiddleware::class,
        'permission.developer' => \App\Http\Middleware\Permissions\Developer\UserActionPerm::class,
        'permission.clientevent' => \App\Http\Middleware\Permissions\Client\ClientEvent::class,
        'permission.subaction' => \App\Http\Middleware\Permissions\Worksheet\SubAction\SubActionMiddleware::class,
        'permission.redemptions' => \App\Http\Middleware\Permissions\Worksheet\Modules\RedemptionsMiddleware::class,
        'tasklist.setmanager' => \App\Http\Middleware\TaskList\SetManagerMiddleware::class,
        'director.request' => \App\Http\Middleware\DirectorRequestMiddleware::class,
        'subaction.iswork' => \App\Http\Middleware\Worksheet\SubAction\SubActionChangeMiddlewar::class,
        'permission.newstock.list' => \App\Http\Middleware\Permissions\Car\Car\CarAccessListMiddleware::class,
        'permission.newstock.store' => \App\Http\Middleware\Permissions\Car\Car\CarAccessStoreMiddleware::class,
        'permission.newstock.show' => \App\Http\Middleware\Permissions\Car\Car\CarAccessShowMiddleware::class,
        'permission.newstock.edit' => \App\Http\Middleware\Permissions\Car\Car\CarAccessEditMiddleware::class,
        'permission.complectation.list'      => \App\Http\Middleware\Permissions\Car\Complectation\ComplectationAccessListMiddleware::class,
        'permission.complectation.store'     => \App\Http\Middleware\Permissions\Car\Complectation\ComplectationAccessStoreMiddleware::class,
        'permission.complectation.show'      => \App\Http\Middleware\Permissions\Car\Complectation\ComplectationAccessShowMiddleware::class,
        'permission.complectation.edit'      => \App\Http\Middleware\Permissions\Car\Complectation\ComplectationAccessEditMiddleware::class,
        'permission.complectation.delete'    => \App\Http\Middleware\Permissions\Car\Complectation\ComplectationAccessDeleteMiddleware::class,
        'permission.complectation.restore'   => \App\Http\Middleware\Permissions\Car\Complectation\ComplectationAccessRestoreMiddleware::class,
        'potokbit'                          => \App\Http\Middleware\PotokBitMiddlewareLog::class,
        'carfilter'                         => \App\Http\Middleware\Car\CarFilterMiddleware::class,
        'blockroute'                        => \App\Http\Middleware\System\AdminWorkMiddleware::class,
    ];
}

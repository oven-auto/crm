<?php

use App\Http\Controllers\Api\v1\Back\Audit\AuditListController;
use App\Http\Controllers\Api\v1\Back\Audit\CRUD\AuditController;
use App\Http\Controllers\Api\v1\Back\Audit\CRUD\QuestionController;
use App\Http\Controllers\Api\v1\Back\Audit\CRUD\SubAnswerController;
use App\Http\Controllers\Api\v1\Back\Audit\CRUD\SubQuestionController;
use App\Http\Controllers\Api\v1\Back\Audit\Manager\AuditAssistantController;
use App\Http\Controllers\Api\v1\Back\Audit\Master\AuditMasterController;
use App\Http\Controllers\Api\v1\Back\Audit\Master\AuditRecordController;
use App\Http\Controllers\Api\v1\Back\Bodywork\BodyworkController;
use App\Http\Controllers\Api\v1\Back\Car\CarCloneController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Back\Car\Complectation\ComplectationController;
use App\Http\Controllers\Api\v1\Back\Car\Color\ColorController;
use App\Http\Controllers\Api\v1\Back\Car\Marker\MarkerController;
use App\Http\Controllers\Api\v1\Back\Car\Option\OptionController;
use App\Http\Controllers\Api\v1\Back\Car\CarController;
use App\Http\Controllers\Api\v1\Back\Car\CarCountController;
use App\Http\Controllers\Api\v1\Back\Car\CarImageController;
use App\Http\Controllers\Api\v1\Back\Car\CarOwnerController;
use App\Http\Controllers\Api\v1\Back\Car\Collector\CollectorController;
use App\Http\Controllers\Api\v1\Back\Car\Color\ImageColorController;
use App\Http\Controllers\Api\v1\Back\Car\Complectation\ComplectationListController;
use App\Http\Controllers\Api\v1\Back\Car\Complectation\PriceComplectationController;
use App\Http\Controllers\Api\v1\Back\Car\OrderType\OrderTypeController;
use App\Http\Controllers\Api\v1\Back\Car\DeliveryTerm\DeliveryTermController;
use App\Http\Controllers\Api\v1\Back\Car\DetailingCost\DetailingCostController;
use App\Http\Controllers\Api\v1\Back\Car\Factory\FactoryController;
use App\Http\Controllers\Api\v1\Back\Car\Option\PriceOptionController;
use App\Http\Controllers\Api\v1\Back\Car\TradeMarker\TradeMarkerController;
use App\Http\Controllers\Api\v1\Back\Car\Tuning\TuningController;
use App\Http\Controllers\Api\v1\Back\Client\Car\ClientChangeCarOwner;
use App\Http\Controllers\Api\v1\Back\Client\ClientEventTemplateController;
use App\Http\Controllers\Api\v1\Back\Credit\ContentController;
use App\Http\Controllers\Api\v1\Back\Credit\StatusController;
use App\Http\Controllers\Api\v1\Back\Credit\TacticController;
use App\Http\Controllers\Api\v1\Back\Director\OperativeReportController;
use App\Http\Controllers\Api\v1\Back\Director\RealisationController;
use App\Http\Controllers\Api\v1\Back\Director\SaleFunnelController;
use App\Http\Controllers\Api\v1\Back\Director\StockStructureController;
use App\Http\Controllers\Api\v1\Back\DiscountCar\DiscountCarController;
use App\Http\Controllers\Api\v1\Back\DiscountCar\DiscountListController;
use App\Http\Controllers\Api\v1\Back\Payment\PaymentController as CRUDPaymentController;
use App\Http\Controllers\Api\v1\Back\Service\ServiceCategoryController;
use App\Http\Controllers\Api\v1\Back\Service\ServiceController;
use App\Http\Controllers\Api\v1\Back\Service\ServiceProviderController;
use App\Http\Controllers\Api\v1\Back\TargetModel\TargetModelController;
use App\Http\Controllers\Api\v1\Back\TaskList\OverdueCountController;
use App\Http\Controllers\Api\v1\Back\UsedCar\UsedCarController;
use App\Http\Controllers\Api\v1\Back\Worksheet\CommentListController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Credit\CreditCommentController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Credit\CreditController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Credit\CreditListController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\ReserveNewCarController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\RedemptionController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\ContractController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\ContractListController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\PaymentReserveController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\ReserveCommentController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\ReserveListController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\DiscountReserveController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\PlannedPaymentController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\TradeInReserveController;
use App\Http\Controllers\Api\v1\Services\Select\BodyWorkSelectController;
use App\Http\Controllers\Api\v1\Services\Select\BrandSelectController;
use App\Http\Controllers\Api\v1\Services\Select\CarBuisnesStatusController;
use App\Http\Controllers\Api\v1\Services\Select\CarLogisticStatusController;
use App\Http\Controllers\Api\v1\Services\Select\CarStateController;
use App\Http\Controllers\Api\v1\Services\Select\CollectorController as SelectCollectorController;
use App\Http\Controllers\Api\v1\Services\Select\FormOwnerController;
use App\Http\Controllers\Api\v1\Services\Select\MarkerSelectController;
use App\Http\Controllers\Api\v1\Services\Select\MarkSelectController;
use App\Http\Controllers\Api\v1\Services\Select\ModuleListController;
use App\Http\Controllers\Api\v1\Services\Select\OrderController;
use App\Http\Controllers\Api\v1\Services\Select\PaymentController;
use App\Http\Controllers\Api\v1\Services\Select\ReasonRefusalController;
use App\Http\Controllers\Api\v1\Services\Select\SalePriorityController;
use App\Http\Controllers\Api\v1\Services\Select\TuningController as SelectTuningController;
use App\Http\Controllers\Api\v1\Services\Select\UserSelectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Integration\PotokBit\PotokBitController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\ReserveLisingerController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Service\ServiceCommentController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Service\ServiceListController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Service\ServiceWorksheetController;
use App\Http\Controllers\Api\v1\Services\Select\DealTypeController;
use App\Http\Controllers\Api\v1\Services\TemplateProcessController;
use App\Http\Middleware\Permissions\Audit\AuditCRUDMiddleware;
use App\Http\Middleware\Permissions\Audit\AuditMasterMiddleware;
use App\Models\Audit\AuditMaster;

Route::get('test', [HomeController::class, 'test']);

/**************************************************************************************
 * /**************************************************************************************
 * /**************************************************************************************
 * INTEGRATION
 **************************************************************************************
 **************************************************************************************
 **************************************************************************************/

/**
 * POTOKBIT
 **/
Route::prefix('integrations')->group(function () {
    Route::prefix('potokbit')->middleware(['robot', 'potokbit'])->group(function () {
        Route::post('', [PotokBitController::class, 'index']);
    });
});

/**
 * CMEXPERT
 **/
Route::prefix('smexpert')->namespace('\App\Http\Controllers\Api\v1\SMExpert')->group(function () {

    Route::post('create/redemptions/{redemption}', 'Deliver\CreateRedemptionController')
        ->middleware(['corsing', 'userfromtoken', 'permission.redemptions:appraisal,redemption_appraisal']);

    // Route::get('gain/brands', 'Gain\BrandController');
    // Route::get('gain/test', 'Gain\MarkController');
    Route::get('deliver/brands', 'Deliver\BrandController');
    Route::get('deliver/marks', 'Deliver\MarkController');
});


Route::get('telegram/get', [App\Http\Controllers\Telegram\TelegramController::class, 'get']);


Route::get('time', function () {
    return response()->json(['datetime' => date('Y-m-d\TH:i'),]);
});





/**************************************************************************************
 * /**************************************************************************************
 * /**************************************************************************************
 * AUTH
 **************************************************************************************
 **************************************************************************************
 **************************************************************************************/
Route::prefix('auth')->namespace('\App\Http\Controllers\Api\v1\Auth')->group(function () {
    Route::post('login', 'LoginController@index');
    Route::get('logout', 'LogoutController@index');
    Route::get('check', 'LoginController@check')->middleware(['corsing', 'userfromtoken']);
    Route::post('register', 'RegisterController@register');
});




Route::middleware(['userfromtoken'])->group(function () {

    Route::prefix('audits')->group(function(){
        Route::get('',                              [AuditListController::class, 'index'])->withTrashed();
        Route::get('count',                         [AuditListController::class, 'count'])->withTrashed();

        Route::apiResource('record', AuditRecordController::class)->except('edit','create',);
        
        Route::middleware(AuditMasterMiddleware::class)->group(function(){
            Route::patch('master/{master}/restore',     [AuditMasterController::class, 'restore'])->withTrashed();
            Route::get('master/check',                  [AuditMasterController::class, 'check']);
            Route::patch('/master/{master}/arbitr',     [AuditMasterController::class, 'arbitr']);
            Route::apiResource('master',                AuditMasterController::class)->except(['edit','create',]);
        });        

        Route::apiResource('assistant',                 AuditAssistantController::class)->except(['edit', 'create', 'delete','edit']);

        Route::middleware(AuditCRUDMiddleware::class)->group(function(){
            Route::post('audits/{audit}',               [AuditController::class, 'clone']);
            Route::apiResource('audits',                AuditController::class)->except(['edit', 'create']);
            Route::put('audits/{audit}/restore',        [AuditController::class, 'restore']);

            Route::patch('questions/sort',              [QuestionController::class, 'sort']);
            Route::apiResource('questions',             QuestionController::class)->except(['edit', 'create']);
            Route::put('questions/{question}/restore',  [QuestionController::class, 'restore']);

            Route::patch('subquestions/sort',           [SubQuestionController::class, 'sort']);
            Route::apiResource('subquestions',          SubQuestionController::class)->except(['edit', 'create']);

            Route::patch('subanswers/sort',             [SubAnswerController::class, 'sort']);
            Route::apiResource('subanswers',            SubAnswerController::class)->except(['edit', 'create']);
        }); 
    });



    Route::get('colors',                        [App\Http\Controllers\Api\v1\Services\Select\ColorController::class, 'index']); //базовые цвета
    Route::get('motortransmissions',            [App\Http\Controllers\Api\v1\Services\Select\MotorTransmissionSelectController::class, 'index']); //трансмиссии (автомат, вариатор итд)
    Route::get('motordrivers',                  [App\Http\Controllers\Api\v1\Services\Select\MotorDriverSelectController::class, 'index']); //привода (передний задний и тд)
    Route::get('motortypes',                    [App\Http\Controllers\Api\v1\Services\Select\MotorTypeSelectController::class, 'index']); //типы моторов (бензин дизель и тд)



    Route::prefix('services')->group(function () {
        Route::prefix('html')->group(function () { //МАРШРУТЫ ПОЛУЧЕНИЯ СПИСКОВ ДЛЯ HTML
            Route::prefix('select')->group(function () {
                Route::get('cartypes',           [CarBuisnesStatusController::class, 'type']);

                Route::get('dealtypes',          [DealTypeController::class, 'index']);

                Route::get('salepriorities',     [SalePriorityController::class, 'index']);

                //all form owner
                Route::get('formowners',         [FormOwnerController::class, 'index']);

                //Все бренды
                Route::get('brands',             [BrandSelectController::class, 'all']); 

                 //только дилерские бренды
                Route::get('dealerbrands',       [BrandSelectController::class, 'dealer']);

                //модели все или если указан brand_id то только модели бренда ??????
                Route::get('marks',              [MarkSelectController::class, 'index']); 

                //Все моделиv ????????????????
                Route::get('allmarks',           [MarkSelectController::class, 'all']);                
                
                Route::get('motortransmissions', [App\Http\Controllers\Api\v1\Services\Select\MotorTransmissionSelectController::class, 'index']); //трансмиссии (автомат, вариатор итд)
                Route::get('motordrivers',       [App\Http\Controllers\Api\v1\Services\Select\MotorDriverSelectController::class, 'index']); //привода (передний задний и тд)
                Route::get('motortypes',         [App\Http\Controllers\Api\v1\Services\Select\MotorTypeSelectController::class, 'index']); //типы моторов (бензин дизель и тд)
                Route::get('toxic',              [App\Http\Controllers\Api\v1\Services\Select\MotorToxicController::class, 'index']); //токсичность моторов (евро 2 евро 3 и тд)
                Route::get('factories',          [App\Http\Controllers\Api\v1\Services\Select\CountryFactorySelectController::class, 'index']); //места производства
                Route::get('colors',             [App\Http\Controllers\Api\v1\Services\Select\ColorController::class, 'index']); //базовые цвета
                Route::get('markcolors',         [App\Http\Controllers\Api\v1\Services\Select\ColorController::class, 'mark']); //дилерские цвета
                
                //список маркеров логиста контрмарка
                Route::get('markers',            [MarkerSelectController::class, 'index']); 
                
                //список товарных признаков
                Route::get('trademarkers',       [MarkerSelectController::class, 'trademarker']); 
                
                //список типы заказа
                Route::get('ordertypes',         [OrderController::class, 'types']); 
                
                //список условий поставки
                Route::get('deliveryterms',      [OrderController::class, 'deliveryterms']); 
                
                //список детализации стоимости
                Route::get('detailingcosts',     [OrderController::class, 'detailingcosts']); 
                
                //список тюнинга
                Route::get('tunings',            [SelectTuningController::class, 'devices']); 
                
                //Список типа кузовов
                Route::get('bodyworks',          [BodyWorkSelectController::class, 'index']); 

                //Список акронимов типа кузова
                Route::get('bodyacronyms',       [BodyWorkSelectController::class, 'acronym']);

                //список типов ТС
                Route::get('vehicletypes',       [BodyWorkSelectController::class, 'vehicletypes']);
                
                //список пользователей
                Route::get('users',              [UserSelectController::class, 'index'])->withTrashed(); 
                
                //Список залогодателей (держатель залога)
                Route::get('collectors', [SelectCollectorController::class, 'index']);
                
                //Список типов оплаты
                Route::get('payments', [PaymentController::class, 'index']);

                //Алиасы моделей
                Route::get('markaliases', [MarkSelectController::class, 'getaliases']);
                
                //Список причин отказа
                Route::get('reasons', [ReasonRefusalController::class, 'index']);

                //Список типов бизнес модулей РЛ
                Route::get('modules', [ModuleListController::class, 'index']);
                
                //Список бизнес статусов нового авто
                Route::get('car_status_types', [CarBuisnesStatusController::class, 'index']);

                //Список логистических статусов нового авто
                Route::get('car_states', [CarLogisticStatusController::class, 'index']);

                //Синоним Список логистических статусов нового авто
                Route::get('carstates', [CarStateController::class, 'list']);

                //СПИСОК ТИПОВ РАПОТРА
                Route::get('report_types', [CarBuisnesStatusController::class, 'report']);

                //TODO настроить выдачу шаблонов
                Route::get('templateprocess', [TemplateProcessController::class, 'index']);
                
                Route::get('creditstatuses',  [StatusController::class, 'index']);
            });
        });
    });



    Route::prefix('listing')->middleware(['corsing', 'userfromtoken'])->namespace('\App\Http\Controllers\Api\v1\Listing')->group(function () {
        Route::get('vehicletypes', 'VehicleTypeController');
        Route::get('users', 'UserController@index')->withTrashed();
        Route::get('zones', 'ZoneController@index');
        Route::get('chanels', 'ChanelController@index');
        Route::get('structures', 'StructureController');
        Route::get('appeals', 'AppealController');
        Route::get('worksheet/statuses', 'WorksheetController');
        Route::get('worksheetaction/statuses', 'WorksheetActionStatusController');
        Route::get('eventpersonal', function () {
            return response()->json([
                'data' => [
                    ['name' => 'Все', 'id' => 0],
                    ['name' => 'Личные', 'id' => 1]
                ],
                'success' => 1
            ]);
        });
        Route::get('/trafic/buttons', 'TraficButtonFilterController');
        Route::get('/company/structures', 'CompanyStructureController');
        Route::prefix('redemptions')->group(function () {
            Route::get('types', '\App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Listing\RedemptionListingController@type');
            Route::get('signs/{type?}', '\App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Listing\RedemptionListingController@sign');
        });
    });



    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * PRODUCTS IN TRAFIC
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::middleware(['corsing', 'userfromtoken'])->group(function () {
        //middleware сделан в контролере
        Route::resource('serviceproducts', '\App\Http\Controllers\Api\v1\Back\ServiceProduct\ServiceProductController')
            ->except(['edit', 'create']);
        //middleware сделан в контролере
        Route::resource('productgroups', '\App\Http\Controllers\Api\v1\Back\ServiceProduct\ProductGroupController')
            ->except(['edit', 'create']);
    });



    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * USERS
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::middleware(['corsing', 'userfromtoken'])->group(function () {
        //middleware сделан в контролере
        Route::resource('users', '\App\Http\Controllers\Api\v1\Back\User\UserController')
            ->except(['edit', 'create'])->withTrashed();
        //middleware сделан в контролере
        Route::resource('roles', '\App\Http\Controllers\Api\v1\Back\User\RoleController')
            ->except(['edit', 'create']);
        Route::resource('permissions', '\App\Http\Controllers\Api\v1\Back\User\PermissionController')
            ->except(['edit', 'create', 'store', 'delete']);
    });



    /**
     * ЭКСПОРТЫ обернуты правами доступа
     */
    // Route::prefix('export')->middleware(['userfromtoken'])->group(function () {
    //     Route::get('trafic', '\App\Http\Controllers\Api\v1\Back\Trafic\TraficExportController@index')
    //         ->middleware(['permission.trafic.list:trafic_export']);
    //     //Экспорт клиентов в excel
    //     Route::get('client', '\App\Http\Controllers\Api\v1\Back\Client\ClientExportController')
    //         ->middleware(['permission.trafic.list:client_export']);
    //     Route::get('cars',      [CarExcelController::class, 'index']);
    // });



    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * TRAFIC
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::prefix('trafic')->middleware(['corsing', 'userfromtoken'])->namespace('\App\Http\Controllers\Api\v1\Back\Trafic')->group(function () {

        //пометить трафик как удаленный
        Route::delete('/{trafic}', 'TraficController@delete')
            ->middleware(['permission.trafic.delete']);

        Route::prefix('links')->group(function () {
            Route::get('/', 'TraficLinkController@index');
            Route::post('/{trafic}', 'TraficLinkController@store');
            Route::patch('/{link}', 'TraficLinkController@update');
            Route::delete('/{link}', 'TraficLinkController@delete');
            Route::get('/count', 'TraficLinkController@count');
        });

        Route::get('owner', 'TraficOwnerController');

        Route::get('product/statuses', 'TraficProductStatusList');
        //получить список зон трафика
        Route::get('zones', 'TraficZoneController@index');
        //получить список каналов трафика
        Route::get('chanels', 'TraficChanelController@index');
        //получить список полов объекта трафика
        Route::get('sexlist', 'TraficSexController@index');
        //получить список компаний трафика
        Route::get('companies', 'TraficCompanyController@index');
        //получить список структур выбраной компании трафика, brand_id это id компании
        Route::get('structures/{brand_id}', 'TraficStructureController@index');
        //получить список обращений указаной структуры компании трафика, id - структура
        Route::get('appeals/{id?}', 'TraficAppealController@index');
        //получить список товаров и услуг, в зависимости от выбранного обращения трафика, id - обращения
        Route::get('needs/{company_id?}', 'TraficNeedController@index');
        //получить список моделей по id компании
        Route::get('models/{company_id?}', 'TraficNeedController@models');
        //Получить список товаров по обращению
        Route::get('appealneeds/{trafic_appeal_id?}', 'TraficNeedController@appealneed');
        //получить список задач трафика - !!!с 14-02-23 не используется!!!
        Route::get('tasks', 'TraficTaskController@index');
        //получить список пользователей являющихся сотрудниками указанной структуры трафика и
        //обрабатывающими указанное обращение, structure_id - структура, appeal_id - обращение
        Route::get('users/{structure_id?}/{appeal_id?}', 'TraficUserController@index');
        //получить список статусов трафика
        Route::get('statuses', 'TraficStatusController@index');
        //получить список типов аудита трафика
        Route::get('standarts', 'StandartController');
        //Получить список статусов аудита пройден\не пройден\отсутсвует
        Route::get('auditresults', 'AuditStatusListController');

        Route::get('comments/{trafic_id}', 'TraficCommentController');

        //кол-во всех трафикаов +
        Route::get('count', 'TraficCountController@index')
            ->middleware(['permission.trafic.list:trafic_list']);

        //список всех трафиков
        Route::get('list', 'TraficController@index')
            ->withTrashed()
            ->middleware(['permission.trafic.list:trafic_list']);

        //создание трафика
        Route::post('create', 'TraficController@store')
            ->middleware(['permission.trafic.create:trafic_add']);

        //упустить трафик
        Route::patch('close/{trafic}', 'TraficController@close')
            ->withTrashed()
            ->middleware([
                'permission.trafic.close',
            ]);
        
        Route::prefix('audit')->group(function(){
             //загрузить загруженные аудиты трафика
            Route::post('/{trafic}', 'TraficAuditController@load')
                ->middleware(['permission.trafic.show:trafic_files_load',]);

            //Изменить загруженные аудиты трафика
            Route::patch('/{trafic_processing}', 'TraficAuditController@update')
                ->middleware(['permission.trafic.show:trafic_files_load',]);

            //показать фаилы аудит трафика, id = конкретного аудита
            Route::get('/{trafic_processing}', 'TraficAuditController@show')
                ->middleware(['permission.trafic.show:trafic_files_show',]);
        });

       

        Route::prefix('files')->group(function () {
            Route::get('/{trafic}', 'TraficFileController@index');
            Route::post('/{trafic}', 'TraficFileController@store');
            Route::delete('/{file}', 'TraficFileController@destroy');
        });



        //просмотр трафика
        Route::get('{trafic}', 'TraficController@edit')
            ->withTrashed()
            ->middleware([
                'permission.trafic.show:trafic_show', //просмотр где я автор, менеджер
                'permission.trafic.showalien:trafic_show_alien,show_trafic_without_manager,show_waiting_for_my_appeals,show_all_from_my_appeals',
                'permission.trafic.showdraft:show_trafic_draft'
            ]);

        //изменение трафика
        Route::patch('{trafic}', 'TraficController@update')
            ->withTrashed()
            ->middleware([
                'permission.trafic.show:trafic_update',
                'permission.trafic.showalien:trafic_update_alien,update_trafic_without_manager,update_waiting_for_my_appeals,update_all_from_my_appeals'
            ]);
    });



    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * NEW CAR
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::prefix('payments')->middleware(['corsing', 'userfromtoken'])->group(function () {
        Route::patch('/{payment}', [CRUDPaymentController::class, 'update']);
    });



    Route::prefix('cars')->middleware(['corsing', 'userfromtoken',])->group(function () {

        Route::get('/clone/{car}', [CarCloneController::class, 'clone']);

        Route::delete('/image/{car}', [CarImageController::class, 'delete']);

        Route::prefix('factories')->group(function () {
            Route::get('/',                 [FactoryController::class, 'index']);
            Route::post('/',                [FactoryController::class, 'store']);
            Route::patch('/{factory}',      [FactoryController::class, 'update'])->withTrashed();
            Route::get('/{factory}',        [FactoryController::class, 'show'])->withTrashed();
            Route::delete('/{factory}',     [FactoryController::class, 'destroy']);
            Route::patch('{factory}/restore', [FactoryController::class, 'revert'])->withTrashed();
        });



        Route::prefix('collectors')->group(function () {
            Route::get('/',                       [CollectorController::class, 'index']);
            Route::post('/',                      [CollectorController::class, 'store']);
            Route::patch('/{collector}',          [CollectorController::class, 'update'])->withTrashed();
            Route::get('/{collector}',            [CollectorController::class, 'show'])->withTrashed();
            Route::delete('/{collector}',         [CollectorController::class, 'destroy']);
            Route::patch('{collector}/restore',   [CollectorController::class, 'revert'])->withTrashed();
        });



        Route::prefix('tunings')->group(function () {
            Route::get('/',                         [TuningController::class, 'index']);
            Route::post('/',                        [TuningController::class, 'store']);
            Route::patch('/{tuning}',               [TuningController::class, 'update'])->withTrashed();
            Route::get('/{tuning}',                 [TuningController::class, 'show'])->withTrashed();
            Route::delete('/{tuning}',              [TuningController::class, 'destroy']);
            Route::patch('{tuning}/restore',        [TuningController::class, 'restore'])->withTrashed();
        });




        /**
         * МАРШРУТЫ КРУДА КОМПЛЕКТАЦИИ
         */
        Route::prefix('complectations')->group(function () {

            Route::prefix('prices')->group(function () {
                Route::get('/',                         [PriceComplectationController::class, 'index']);
                Route::get('/{complectationprice}',     [PriceComplectationController::class, 'show']);
                Route::post('/',                        [PriceComplectationController::class, 'store']);
            });

            Route::get('', [ComplectationController::class, 'index'])->middleware('permission.complectation.list');
            Route::get('search', [ComplectationController::class, 'search'])->middleware('permission.complectation.list');
            Route::post('', [ComplectationController::class, 'store'])->middleware('permission.complectation.store');
            Route::get('{complectation}', [ComplectationController::class, 'show'])->withTrashed()->middleware('permission.complectation.show');
            Route::patch('{complectation}', [ComplectationController::class, 'update'])->withTrashed()->middleware('permission.complectation.edit');
            Route::delete('{complectation}', [ComplectationController::class, 'delete'])->middleware('permission.complectation.delete');
            Route::patch('{complectation}/restore', [ComplectationController::class, 'restore'])->withTrashed()->middleware('permission.complectation.restore');
        });



        /**
         * МАРШРУТЫ КРУДА ДИЛЕРСКИХ ЦВЕТОВ
         */
        Route::prefix('colors')->group(function () {
            Route::prefix('images')->group(function(){
                Route::get('',                      [ImageColorController::class, 'index']);
                Route::post('',                     [ImageColorController::class, 'store']);
                Route::patch('{image}',             [ImageColorController::class, 'update']);
                Route::delete('{image}',            [ImageColorController::class, 'delete']);
            });    
               
            Route::get('',                          [ColorController::class, 'index']);
            Route::get('list',                      [ColorController::class, 'list']);
            Route::post('',                         [ColorController::class, 'store']);
            Route::get('{dealercolor}',             [ColorController::class, 'show'])->withTrashed();
            Route::patch('{dealercolor}',           [ColorController::class, 'update'])->withTrashed();
            Route::delete('{dealercolor}',          [ColorController::class, 'delete']);
            Route::patch('{dealercolor}/restore',   [ColorController::class, 'restore'])->withTrashed();
        });



        /**
         * МАРШРУТЫ КРУДА МАРКЕРА ЛОГИСТА
         */
        Route::prefix('markers')->group(function () {
            Route::get('',                      [MarkerController::class, 'index']);
            Route::post('',                     [MarkerController::class, 'store']);
            Route::get('{marker}',              [MarkerController::class, 'show'])->withTrashed();
            Route::patch('{marker}',            [MarkerController::class, 'update'])->withTrashed();
            Route::delete('{marker}',           [MarkerController::class, 'delete']);
            Route::patch('{marker}/restore',    [MarkerController::class, 'restore'])->withTrashed();
        });



        /**
         * МАРШРУТЫ ТОВАРНЫЙ ПРИЗНАК
         */
        Route::prefix('trademarkers')->group(function () {
            Route::get('',                      [TradeMarkerController::class, 'index']);
            Route::post('',                     [TradeMarkerController::class, 'store']);
            Route::get('{marker}',              [TradeMarkerController::class, 'show'])->withTrashed();
            Route::patch('{marker}',            [TradeMarkerController::class, 'update'])->withTrashed();
            Route::delete('{marker}',           [TradeMarkerController::class, 'delete']);
            Route::patch('{marker}/restore',    [TradeMarkerController::class, 'restore'])->withTrashed();
        });



        /**
         * МАРШРУТЫ КРУДА ТИП ЗАКАЗА
         */
        Route::prefix('ordertypes')->group(function () {
            Route::get('',                          [OrderTypeController::class, 'index']);
            Route::post('',                         [OrderTypeController::class, 'store']);
            Route::get('{ordertype}',               [OrderTypeController::class, 'show'])->withTrashed();
            Route::patch('{ordertype}',             [OrderTypeController::class, 'update'])->withTrashed();
            Route::delete('{ordertype}',            [OrderTypeController::class, 'delete']);
            Route::patch('{ordertype}/restore',     [OrderTypeController::class, 'restore'])->withTrashed();
        });



        /**
         * МАРШРУТЫ КРУДА УСЛОВИЕ ДОСТАВКИ
         */
        Route::prefix('deliveryterms')->group(function () {
            Route::get('',                              [DeliveryTermController::class, 'index']);
            Route::post('',                             [DeliveryTermController::class, 'store']);
            Route::get('{deliveryterm}',                [DeliveryTermController::class, 'show'])->withTrashed();
            Route::patch('{deliveryterm}',              [DeliveryTermController::class, 'update'])->withTrashed();
            Route::delete('{deliveryterm}',             [DeliveryTermController::class, 'delete']);
            Route::patch('{deliveryterm}/restore',      [DeliveryTermController::class, 'restore'])->withTrashed();
        });



        /**
         * МАРШРУТЫ КРУДА ДЕТАЛИЗАЦИЯ ЦЕНЫ
         */
        Route::prefix('detailingcosts')->group(function () {
            Route::get('',                                  [DetailingCostController::class, 'index']);
            Route::post('',                                 [DetailingCostController::class, 'store']);
            Route::get('{detailingcost}',                   [DetailingCostController::class, 'show'])->withTrashed();
            Route::patch('{detailingcost}',                 [DetailingCostController::class, 'update'])->withTrashed();
            Route::delete('{detailingcost}',                [DetailingCostController::class, 'delete']);
            Route::patch('{detailingcost}/restore',         [DetailingCostController::class, 'restore'])->withTrashed();
        });



        /**
         * МАРШРУТЫ КРУДА ОПЦИЙ
         */
        Route::prefix('options')->group(function () {
            Route::prefix('prices')->group(function () {
                Route::get('/',                  [PriceOptionController::class, 'index']);
                Route::get('/{optionPrice}',     [PriceOptionController::class, 'show']);
                Route::post('/',                 [PriceOptionController::class, 'store']);
            });

            Route::get('',                      [OptionController::class, 'index']);
            Route::post('',                     [OptionController::class, 'store']);
            Route::get('{option}',              [OptionController::class, 'show'])->withTrashed();
            Route::patch('{option}',            [OptionController::class, 'update'])->withTrashed();
            Route::delete('{option}',           [OptionController::class, 'delete']);
            Route::patch('{option}/restore',    [OptionController::class, 'restore'])->withTrashed();
        });



        /**ДООЦЕНКА АВТОМОБИЛЯ */
        Route::prefix('overprice')->group(function () {
            Route::get('{car}',                 [CarController::class, 'getOverPrice']);
            Route::post('{car}',                [CarController::class, 'makeOverPrice']);
        });



        Route::get('count', [CarCountController::class, 'count']);



        Route::prefix('owners')->group(function () {
            Route::post('/{car}', [CarOwnerController::class, 'store']);
            Route::delete('/{car}', [CarOwnerController::class, 'destroy']);
        });



        /**
         * МАРШРУТЫ КРУДА СОБСТВЕННО САМОГО НОВОГО АВТОМОБИЛЯ
         */
        Route::prefix('')->group(function () {
            Route::get('',                      [CarController::class, 'index'])->middleware('permission.newstock.list');
            Route::post('',                     [CarController::class, 'store'])->middleware('permission.newstock.store');
            Route::get('{car}',                 [CarController::class, 'show'])->middleware('permission.newstock.show')->withTrashed();
            Route::patch('{car}',               [CarController::class, 'update'])->middleware('permission.newstock.edit');
            Route::get('{car}/history',         [CarController::class, 'history'])->middleware('permission.newstock.edit');
            Route::get('{car}/tuning',          [CarController::class, 'tuning'])->middleware('permission.newstock.edit');
            Route::delete('{car}',              [CarController::class, 'destroy']);
            Route::patch('restore/{car}',       [CarController::class, 'restore']);
        });
    });



    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * CLIENT
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::prefix('client')->middleware(['corsing', 'userfromtoken'])->namespace('\App\Http\Controllers\Api\v1\Back\Client')->group(function () {

        Route::prefix('links')->group(function () {
            Route::get('{client}', 'ClientLinkController@index');
            Route::post('{client}', 'ClientLinkController@store');
            Route::delete('{clientlink}', 'ClientLinkController@delete');
        });

        //CRUD Связей клиентов с клиентами
        Route::prefix('unions')->group(function () {
            Route::get('count/{count}', '\App\Http\Controllers\Api\v1\Back\Client\Union\ClientUnionController@amount');
            Route::get('search', '\App\Http\Controllers\Api\v1\Back\Client\Union\SearchClientController@search');
            Route::get('{client}', '\App\Http\Controllers\Api\v1\Back\Client\Union\ClientUnionController@show');
            Route::post('{client}', '\App\Http\Controllers\Api\v1\Back\Client\Union\ClientUnionController@store');
            Route::delete('{client}', '\App\Http\Controllers\Api\v1\Back\Client\Union\ClientUnionController@destroy');
        });

        Route::prefix('event/templates')->group(function(){
            Route::get('/',                 [ClientEventTemplateController::class, 'index']);
            Route::post('/',                [ClientEventTemplateController::class, 'store']);
            Route::get('/{id}',             [ClientEventTemplateController::class, 'show']);
            Route::patch('/{id}',           [ClientEventTemplateController::class, 'update']);
            Route::delete('/{id}',          [ClientEventTemplateController::class, 'destroy']);
            Route::patch('/{id}/restore',   [ClientEventTemplateController::class, 'restore']);
        });

        //CRUD Ссылок в коммуникации
        Route::prefix('event/links')->group(function () {
            Route::get('/{clientEventStatus}', 'EventLinkController@index');
            Route::post('/', 'EventLinkController@store');
            Route::patch('/{link}', 'EventLinkController@update');
            Route::get('/show/{link}', 'EventLinkController@show');
            Route::delete('/{link}', 'EventLinkController@delete');
        });

        //CRUD фаилов в коммуникации
        Route::prefix('event/files')->group(function () {
            Route::get('/{clientEventStatus}', 'EventFileController@index');
            Route::post('/', 'EventFileController@store');
            Route::get('/show/{file}', 'EventFileController@show');
            Route::delete('/{file}', 'EventFileController@delete');
        });

        //CRUD Исполнителей в коммуникации
        Route::prefix('event/executors')->group(function () {
            Route::post('/{clientEventStatus}', 'EventExecutorController@store');
            Route::delete('/{clientEventStatus}', 'EventExecutorController@delete');
        });

        // Route::resource('events', '\App\Http\Controllers\Api\v1\Back\Client\ClientEventController')
        //     ->except(['create','edit']);

        Route::get('check', 'CheckClientController@check');

        Route::get('eventtypes', 'EventTypeController');

        Route::resource('eventgroups', 'EventGroupController')->only(['index', 'store', 'update', 'show']);

        Route::get('eventstatuses', 'EventStatusController');

        Route::get('events/count', 'ClientEventCountController');

        Route::delete('events/file/{client_event_file}', '\App\Http\Controllers\Api\v1\Back\Client\ClientEventFileDeleteController');

        Route::prefix('events')->group(function () {
            //Замена клиента события
            Route::patch('change/client', 'EventChangeClientController@client');

            //Замена автора события
            Route::patch('change/author', 'EventChangeClientController@author');

            //Маршруты для отчитаться/снять отчет
            Route::prefix('report')->group(function () {
                Route::patch('', 'EventReportController@report');
                Route::delete('', 'EventReportController@deport');
            });

            //Закрыть событие клиента
            Route::get('close', 'EventCloseController@close');

            //Вернуть в работу событие клиента
            Route::get('resume', 'EventCloseController@resume');

            //Создать трафик из события
            Route::post('trafic', '\App\Http\Controllers\Api\v1\Back\Client\CreateEventTrafic');

            //Показать комментарии события
            Route::get('comments/{clientEvent}', '\App\Http\Controllers\Api\v1\Back\Client\EventCommentController');

            //Получить список трафиков
            Route::get('trafics/{eventstatus}', '\App\Http\Controllers\Api\v1\Back\Client\EventTraficListController');
        });
        //РЕСУРС НА CRUD
        Route::resource('events', '\App\Http\Controllers\Api\v1\Back\Client\ClientEventController')->except(['create', 'edit']);

        Route::get('marketing', 'ClientMarketing');

        Route::resource('files', 'ClientFileController')->only(['index', 'store', 'update', 'destroy']);

        //список типов клиентов физ/юр
        Route::get('types', 'ClientTypeController');
        //список типов лояльности
        Route::get('loyalties', 'LoyaltyController@list');
        //Количество клиентов
        Route::get('count', 'ClientCountController');
        //cписок клиентов
        Route::get('list', 'ClientController@index')
            ->middleware(['permission.trafic.list:client_list']);
        //создать клиента
        Route::post('create', 'ClientController@store')
            ->middleware(['permission.trafic.list:client_add']);
        //Получить данные о клиенте для модалки
        Route::get('show/{client}', 'ClientController@show');
        //получить конкретного клиента
        Route::get('{client}', 'ClientController@edit')
            ->middleware(['permission.trafic.list:client_show']);
        //изменить конкретного клиента
        Route::patch('{client}', 'ClientController@update')
            ->middleware(['permission.trafic.list:client_edit']);
        //удалить клиента
        Route::delete('{client}', 'ClientController@destroy')
            ->middleware(['permission.trafic.list:client_delete']);

        Route::prefix('car')->middleware('permission.trafic.list:client_add')->group(function () {
            
            //список брендов
            Route::get('brands', 'BrandCarController');
            //количество машин клиента
            Route::get('amount/{client}', 'ClientCarController@amount');
            //список марок по бренду
            Route::get('marks/{brand}', 'MarkCarController');
            //список кузовов
            Route::get('bodies', 'BodyCarController');
            //добавить машину в клиента
            Route::post('{client}', 'ClientCarController@store');
            //список машин конкретного клиета
            Route::get('/list/{client}', 'ClientCarController@index');
            //Получить конкретную машину
            Route::patch('{car}/owner', [ClientChangeCarOwner::class, 'change']);

            Route::get('{car}', 'ClientCarController@show');
            //изменить машину
            Route::patch('{car}', 'ClientCarController@update');
            //удалить машину
            Route::delete('{car}', 'ClientCarController@destroy');
        });
    });



    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * USED CAR
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::prefix('usedcars')->group(function () {
        Route::get('', [UsedCarController::class, 'index']);
        Route::get('{id}', [UsedCarController::class, 'show']);
    });



    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * WORKSHEET
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::prefix('worksheet')->middleware(['corsing', 'userfromtoken'])->namespace('\App\Http\Controllers\Api\v1\Back\Worksheet')->group(function () {
        /**
         * ОТЧИТАТЬСЯ ЗА РАБОЧИЙ ЛИСТ, ЛИБО ОТМЕНИТЬ ОТЧЕТ
         */
        Route::prefix('report')->group(function () {
            Route::patch('',                        'WorksheetReportController@report');
            Route::delete('',                       'WorksheetReportController@deport');
        });

        /**
         * ССЫЛКИ РЛ
         */
        Route::prefix('links')->group(function () {
            Route::get('/{worksheet}',              'WorksheetLinkController@index');
            Route::post('/{worksheet}',             'WorksheetLinkController@store');
            Route::delete('/{worksheetLink}',       'WorksheetLinkController@delete');
        });

        /**
         * ФАИЛЫ РЛ
         */
        Route::prefix('files')->group(function () {
            Route::get('/{worksheet}',         'WorksheetFileController@index');
            Route::post('/{worksheet}',         'WorksheetFileController@store');
            Route::delete('/{worksheetfile}',     'WorksheetFileController@delete');
        });

        Route::get('client/{client}',               'ClientWorksheetListController');

        /**
         * КОММЕНТАРИИ РЛ
         */
        Route::get('comments',                      [CommentListController::class, 'list']);

        /**
         * Добавить действие для рабочего листа
         * @param mixed $request [worksheet_id, begin_at, end_at, task_id, text]
         */
        Route::prefix('action')
            ->middleware(['permission.worksheet.action:ws_action_executor,ws_action_any'])
            ->group(function () {
                Route::post('', 'Action\WorksheetActionController@store')->middleware('permission.worksheet.complete');
                //Route::patch('', 'Action\WorksheetActionController@status')->middleware('permission.worksheet.complete');
                Route::put('', 'Action\WorksheetActionController@comment');
            });


        /*************************************
         * Поменять клиента в рабочем листе
         * @param mixed $request [worksheet_id = int, client_id = int]
         */
        Route::patch('change/client',               'ChangeClientController@change')
            ->middleware('permission.worksheet.action:ws_subclient_attach');
        /****************************************************************************15.05.23
         * Добавить клиента или менеджера в рабочий лист
         * @param mixed $request [worksheet_id = int, user_id = int|client_id = int]
         */
        Route::post('users',                        'AppendUserController@append');

        Route::post('clients',                      'AppendClientController@append')
            ->middleware('permission.worksheet.action:ws_subclient_attach');

        /****************************************************************************15.05.23
         * Удалить клиента или менеджера из рабочего листа
         * @param mixed $request [worksheet_id = int, user_id = int|client_id = int]
         */
        Route::delete('users',                      'AppendUserController@destroy');

        Route::delete('clients',                    'AppendClientController@destroy')
            ->middleware('permission.worksheet.action:ws_subclient_detach');


        /**
         * ЖУРНАЛ РЛ
         */
        Route::get('',                              'WorksheetController@index')
            ->middleware('permission.worksheet.list');

        Route::get('count',                         'WorksheetCountController')
            ->middleware('permission.worksheet.list');

        /**
         * СОЗДАТЬ РЛ
         */
        Route::post('create',                       'WorksheetController@store')
            ->middleware([
                'worksheet.create.base',
                'permission.worksheet.create'
            ]);

        /**
         * ОТКРЫТЬ РЛ
         */
        Route::get('{worksheet}', 'WorksheetController@show')->middleware(['permission.worksheet.show']);

        /**
         * ЗАКРЫТЬ РЛ
         */
        Route::get('close/{worksheet}', 'WorksheetController@close')->middleware(['permission.worksheet.close']);

        /**
         * ВЕРНУТЬ РЛ В РАБОТУ
         */
        Route::get('revert/{worksheet}', 'WorksheetController@revert')->middleware(['permission.worksheet.revert']);



        /**
         * REDEMPTION MODULE
         */
        Route::prefix('modules')->group(function () {
            Route::prefix('redemptions')->group(function () {

                //Кол-во оценок после фильтрации
                Route::get('count', [RedemptionController::class, 'counter']);

                //вернет все оценки после фильтрации. Параметры фильтрации смотри в App\Http\Filters\WSMRedemptionCarFilter
                Route::get('/', [RedemptionController::class, 'list'])->middleware('permission.redemptions:list,redemption_list');

                //список оценок, указан id рабочего листа, выдаст только оценки этого рл
                Route::get('/{worksheet}', [RedemptionController::class, 'index']);

                //Создать заявку на оценку
                Route::post('{worksheet}', [RedemptionController::class, 'store'])->middleware('permission.redemptions:create');

                //Вернуть оценку в работу
                Route::patch('{redemption}/revert', [RedemptionController::class, 'revert'])
                    ->middleware('permission.redemptions:revert,redemption_revert');

                //Упустить оценку
                Route::patch('{redemption}/close', [RedemptionController::class, 'close'])
                    ->middleware('permission.redemptions:delete,redemption_close');

                //Сохранить расчеты (расчетная цена, предложено, согласовано)
                Route::put('{redemption}', 'Modules\RedemptionController@saveprice')
                    ->middleware('permission.redemptions:update');

                //Список ссылок
                Route::get('links/{redemption}', 'Modules\RedemptionController@links');

                Route::prefix('')->middleware('permission.redemptions:update')->group(function () {
                    //Сохранить ссылку
                    Route::post('links/{redemption}', 'Modules\RedemptionController@storelink');

                    //Получить список комментариев
                    Route::get('comments/{redemption}', 'Modules\RedemptionController@commentList');

                    //Добавить комментарий
                    Route::post('comments/{redemption}', 'Modules\RedemptionController@addComment');

                    //Изменить оценку (по сути только 3 параметра: тип оценки, товарный признак, ожидание)
                    Route::patch('{redemption}', 'Modules\RedemptionController@update');

                    //выкуп/перемещение на склад
                    Route::patch('{redemption}/buy', 'Modules\RedemptionController@buy');
                });
            });


            /**
             * RESERVE NEW CAR MODEULE
             */
            Route::prefix('reserves')->group(function () {
                Route::prefix('planned')->group(function(){
                    Route::get('',          [PlannedPaymentController::class, 'index']);
                    Route::post('',         [PlannedPaymentController::class, 'store']);
                    Route::patch('{id}',    [PlannedPaymentController::class, 'update']);
                    Route::delete('{id}',   [PlannedPaymentController::class, 'destroy']);
                });

                Route::prefix('lising')->group(function(){
                    Route::post('/', [ReserveLisingerController::class, 'append']);
                    Route::delete('/', [ReserveLisingerController::class, 'detach']);
                });
                
                Route::get('/', [ReserveNewCarController::class, 'index']);
                Route::post('/', [ReserveNewCarController::class, 'store']);
                Route::patch('setdates/{reserve}', [ReserveNewCarController::class, 'setdate']);
                Route::delete('setdates/{reserve}', [ReserveNewCarController::class, 'deletedate']);
                Route::patch('{reserve}', [ReserveNewCarController::class, 'update']);
                Route::delete('{reserve}', [ReserveNewCarController::class, 'destroy']);                                         


                Route::prefix('contracts')->group(function () {
                    Route::get('{contract}', [ContractController::class, 'show']);
                    Route::post('/', [ContractController::class, 'store']);
                    Route::patch('{contract}', [ContractController::class, 'update']);
                });

                Route::prefix('discounts')->group(function () {
                    Route::patch('check/{discount}', [DiscountReserveController::class, 'check']);
                    Route::get('/', [DiscountReserveController::class, 'index']);
                    Route::post('/', [DiscountReserveController::class, 'store']);
                    Route::get('/{discount}', [DiscountReserveController::class, 'show']);
                    Route::patch('/{discount}', [DiscountReserveController::class, 'update']);
                    Route::delete('/{discount}', [DiscountReserveController::class, 'destroy']);                    
                });

                Route::prefix('comments')->group(function () {
                    Route::get('', [ReserveCommentController::class, 'index']);
                    Route::post('', [ReserveCommentController::class, 'store']);
                });

                Route::prefix('payments')->group(function () {
                    Route::post('',             [PaymentReserveController::class, 'store']);
                    Route::delete('{pay}',      [PaymentReserveController::class, 'destroy']);
                    Route::patch('{pay}',       [PaymentReserveController::class, 'update']);
                });

                Route::prefix('tradeins')->group(function () {
                    Route::get('{reserve}',     [TradeInReserveController::class, 'index']);
                    Route::patch('{reserve}',   [TradeInReserveController::class, 'attach']);
                    Route::delete('{reserve}',  [TradeInReserveController::class, 'detach']);
                });
            });



            /**
             * СЕРВИСЫ
             */
            //TODO МАРШРУТЫ МОДУЛЯ ДОБАВЛЕНИЯ ПРОДУКТОВ В РЛ
            Route::prefix('services')->group(function() {
                Route::prefix('comments')->group(function(){
                    Route::get('', [ServiceCommentController::class, 'index']);
                    Route::post('', [ServiceCommentController::class, 'store']);
                });

                Route::get('',          [ServiceWorksheetController::class, 'index']);
                Route::post('',         [ServiceWorksheetController::class, 'store']);
                Route::get('{id}',      [ServiceWorksheetController::class, 'show']);
                Route::patch('{id}',    [ServiceWorksheetController::class, 'update']);
                Route::delete('{id}',   [ServiceWorksheetController::class, 'destroy']);
            });



            /**
             * КРЕДИТЫ
             */
            //TODO МАРШРУТЫ ДОБАВЛЕНИЯ КРЕДИТОВ В РЛ
            Route::prefix('credits')->group(function(){
                Route::prefix('comments')->group(function(){
                    Route::get('', [CreditCommentController::class, 'index']);
                    Route::post('', [CreditCommentController::class, 'store']);
                });

                Route::get('',          [CreditController::class, 'index']);
                Route::post('',         [CreditController::class, 'store']);
                Route::get('{id}',      [CreditController::class, 'show']);
                Route::patch('{id}',    [CreditController::class, 'update']);
                Route::delete('{id}',   [CreditController::class, 'destroy']);
            });
        });






        /**
         * WORKSHEET SUBACTION
         */
        //->middleware('permission.worksheet.action:ws_action_executor,ws_action_any')
        Route::prefix('subactions')->group(function () {
            Route::get('{worksheetId}',                 'SubAction\SubActionController@index')->middleware('permission.subaction:show');

            Route::post('',                             'SubAction\SubActionController@store')->middleware('permission.subaction:create');

            Route::prefix('')->middleware('permission.subaction:update')->group(function () {
                Route::get('comments/{subAction}',      'SubAction\SubActionController@comments');
                Route::get('/show/{subAction}',         'SubAction\SubActionController@show');
                Route::patch('{subAction}',             'SubAction\SubActionController@update')->middleware('subaction.iswork');
                Route::delete('{subAction}',            'SubAction\SubActionController@close')->middleware('subaction.iswork');

                Route::prefix('executors')->middleware('subaction.iswork')->group(function () {
                    Route::patch('{subAction}',         'SubAction\SubActionController@append');
                    Route::delete('{subAction}',        'SubAction\SubActionController@remove');
                });

                Route::prefix('reporters')->middleware('subaction.iswork')->group(function () {
                    Route::patch('{subAction}',         'SubAction\SubActionController@report');
                    Route::delete('{subAction}',        'SubAction\SubActionController@deport');
                });
            });
        });
    });






    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * TASK LIST
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::prefix('tasklist')
        ->namespace('\App\Http\Controllers\Api\v1\Back\TaskList')
        ->middleware(['corsing', 'userfromtoken'])
        ->group(
            function () {
                Route::get('trafics', 'TraficListController')->middleware('tasklist.setmanager:manager');
                Route::get('events', 'EventListController')->middleware('tasklist.setmanager:executor');
                Route::get('worksheets', 'WorksheetListController')->middleware('tasklist.setmanager:executor');
                Route::get('overdue', [OverdueCountController::class, 'index'])->middleware('tasklist.setmanager:manager');
            }
        );



    /**************************************************************************************
     * /**************************************************************************************
     * /**************************************************************************************
     * ANALITYC
     **************************************************************************************
     **************************************************************************************
     **************************************************************************************/
    Route::prefix('director')
        ->namespace('\App\Http\Controllers\Api\v1\Back\Director')
        ->middleware(['corsing', 'userfromtoken', 'director.request'])
        ->group(function () {
            Route::get('trafics',       'TraficController');
            Route::get('worksheets',    'WorksheetController');
            Route::get('counter',       'CounterController');
            Route::get('options',       'OptionsController');
            Route::prefix('reports')->group(function(){
                Route::get('worked',    [OperativeReportController::class, 'worked']);                
                Route::get('planned',   [OperativeReportController::class, 'planned']);
                Route::get('paided',    [OperativeReportController::class, 'paided']);
                Route::get('withdebit', [OperativeReportController::class, 'withdebit']);
                Route::get('issued',    [OperativeReportController::class, 'issued']);
                Route::get('saled',     [OperativeReportController::class, 'saled']);
                Route::get('receipt',   [OperativeReportController::class, 'receipt']);
            });
            Route::get('funnel',        [SaleFunnelController::class, 'index']);
            Route::get('stock',         [StockStructureController::class, 'index']);
            Route::get('realisation',   [RealisationController::class, 'index']);
        });




    /**RESERVE LIST */
    Route::prefix('reserves')->middleware('userfromtoken')->group(function () {
        Route::get('/', [ReserveListController::class, 'index']);
        Route::get('count', [ReserveListController::class, 'count']);
    });



    /**CONTRACTS LIST */
    Route::prefix('contracts')->group(function () {
        Route::get('/', [ContractListController::class, 'index']);
        Route::get('count', [ContractListController::class, 'count']);
    });



    /**
     * BODYWORKS
     */
    Route::resource('bodyworks', BodyworkController::class);



    /**
     * DISCOUNT CAR LIST
     */
    Route::prefix('discounts')->group(function () {
        Route::get('/',                         [DiscountCarController::class, 'index']);
        Route::post('/',                        [DiscountCarController::class, 'store']);
        Route::patch('/{discounttype}',         [DiscountCarController::class, 'update'])->withTrashed();
        Route::get('/{discounttype}',           [DiscountCarController::class, 'show'])->withTrashed();
        Route::delete('/{discounttype}',        [DiscountCarController::class, 'destroy']);
        Route::patch('{discounttype}/restore',  [DiscountCarController::class, 'restore'])->withTrashed();
    });



    Route::prefix('discountlist')->group(function () {
        Route::get('/',         [DiscountListController::class, 'index']);
        Route::get('/count',    [DiscountListController::class, 'count']);
    });



    Route::prefix('complectationlist')->group(function(){
        Route::get('/',         [ComplectationListController::class, 'index']);
        Route::get('/count',    [ComplectationListController::class, 'count']);
    });  
    
    

    Route::prefix('')->group(function(){
        Route::get('targets/count', [TargetModelController::class, 'count']);

        Route::apiResource('targets', TargetModelController::class)->except(['edit', 'create']);        
    });



    //TODO МАРШРУТЫ СОЗДАНИЯ ПРОДКУТОВ
    Route::prefix('finservices')->group(function(){
        Route::get('payments',  [\App\Http\Controllers\Api\v1\Back\Service\PaymentController::class, 'index']);
        
        Route::get('providers', [ServiceProviderController::class, 'index']);

        Route::patch('categories/{id}/restore',     [ServiceCategoryController::class, 'restore'])->withTrashed();
        Route::apiResource('categories', ServiceCategoryController::class)->except(['edit']);
        
        Route::patch('{id}/restore',     [ServiceController::class, 'restore'])->withTrashed();
        Route::get('/', [ServiceController::class, 'index']);
        Route::post('', [ServiceController::class, 'store']);
        Route::patch('{id}', [ServiceController::class, 'update']);
        Route::get('{id}', [ServiceController::class, 'show']);
        Route::delete('{id}', [ServiceController::class, 'destroy']);
    });



    //TODO МАРШРУТЫ ДЛЯ СПИСКА ФИНУСЛУГ 
    Route::prefix('servicelist')->group(function() {
        Route::get('',      [ServiceListController::class, 'index']);
        Route::get('count', [ServiceListController::class, 'count']);
    });



    //TODO МАРШРУТЫ ДЛЯ СПИСКА КРЕДИТОВ 
    Route::prefix('creditlist')->group(function() {
        Route::patch('tactics/{id}/restore', [TacticController::class, 'restore']);
        Route::resource('tactics', TacticController::class)->except(['edit']);

        Route::patch('content/{id}/restore', [ContentController::class, 'restore']);
        Route::resource('content', ContentController::class)->except(['edit']);

        Route::get('',      [CreditListController::class, 'index']);
        Route::get('count', [CreditListController::class, 'count']);
    });
});

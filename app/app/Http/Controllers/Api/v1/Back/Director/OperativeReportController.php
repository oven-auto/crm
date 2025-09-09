<?php

namespace App\Http\Controllers\Api\v1\Back\Director;

use App\Http\Controllers\Controller;
use App\Http\Resources\Director\ReceiptCollection;
use App\Http\Resources\Director\ReportCollection;
use App\Services\Analytic\Report\ReportService;
use Illuminate\Http\Request;

class OperativeReportController extends Controller
{
    public function __construct(
        private ReportService $service
    )
    {
        
    }



    /**
     * Клиенты в работе
     */
    public function worked(Request $request)
    {
        $data = $this->service->getWorkedReport($request->all());

        return new ReportCollection($data);
    }



    /**
     * План поступлений
     */
    public function planned(Request $request)
    {
        $data = $this->service->getPlannedReport($request->all());

        return new ReportCollection($data);
    }



    /**
     * Выдача с долгом
     */
    public function withdebit(Request $request)
    {
        $data = $this->service->getWithDebitReport($request->all());

        return new ReportCollection($data);
    }



    /**
     * Полные оплаты
     */
    public function paided(Request $request)
    {
        $data = $this->service->getPaidReport($request->all());
        
        return new ReportCollection($data);
    }



    /**
     * Выдачи
     */
    public function issued(Request $request)
    {
        $data = $this->service->getIssuedReport($request->all());

        return new ReportCollection($data);
    }



    /**
     * Продажи
     */
    public function saled(Request $request)
    {
        $data = $this->service->getSaledReport($request->all());

        return new ReportCollection($data);
    }



    /**
     * Выручка
     */
    public function receipt(Request $request)
    {
        $data = $this->service->getReceiptReport($request->all());
        
        return new ReceiptCollection($data);
    }
}

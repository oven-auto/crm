<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trafic\TraficProductCollection;
use App\Repositories\ServiceProduct\TraficProductRepository;
use App\Repositories\Trafic\AppealRepository;

class TraficNeedController extends Controller
{
    public function __construct(
        private TraficProductRepository $repo
    )
    {
        
    }



    public function index($company_id = 0, )
    {
        $service = new AppealRepository();

        $appeals = $service->getAppealWithProductByCompanyId($company_id);
        
        return (new TraficProductCollection($appeals));
    }



    public function appealneed($trafic_appeal_id = '')
    {
        $products = $this->repo->getProductsToTrafic('onlyservice');

        return response()->json([
            'data' => $products,
            'success' => 1,
        ]);
    }



    public function models($company_id = '')
    {
        $products = $this->repo->getProductsToTrafic('onlymark');

        return response()->json([
            'data' => $products,
            'success' => 1,
        ]);
    }
}

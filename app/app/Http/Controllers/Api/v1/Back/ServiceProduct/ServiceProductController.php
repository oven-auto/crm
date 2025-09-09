<?php

namespace App\Http\Controllers\Api\v1\Back\ServiceProduct;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceProduct\ServiceProductCollection;
use App\Http\Resources\ServiceProduct\ServiceProductSaveResource;
use App\Http\Requests\ServiceProduct\ServiceProductCreate;
use App\Http\Requests\ServiceProduct\ServiceProductFilterRequest;
use App\Repositories\ServiceProduct\ServiceProductRepository;

class ServiceProductController extends Controller
{
    public function __construct(
        private ServiceProductRepository $repo,
        public $subject = 'Услуга',
        public $genus = 'female'
    )
    {
        $this->middleware('permission.developer:product_list')->only('index');
        $this->middleware('permission.developer:product_edit')->only('update');
        $this->middleware('permission.developer:product_delete')->only('delete');
        $this->middleware('permission.developer:product_show')->only('show');
        $this->middleware('permission.developer:product_add')->only('store');

        $this->middleware('notice.message')->only(['store', 'update', 'destroy',]);
    }


    
    public function index(ServiceProductFilterRequest $request)
    {
        $products = $this->repo->get($request->all());

        return new ServiceProductCollection($products);
    }


    
    public function store(ServiceProductCreate $request)
    {
        $product = $this->repo->create($request->getDTO());

        return (new ServiceProductSaveResource($product));
    }


    
    public function show(int $id)
    {
        $product = $this->repo->getById($id);

        return new ServiceProductSaveResource($product);
    }


    
    public function update($id, ServiceProductCreate $request)
    {
        $product = $this->repo->update($id, $request->getDTO());

        return (new ServiceProductSaveResource($product));
    }

    

    public function destroy(int $id)
    {
        $product = $this->repo->delete($id);

        return (new ServiceProductSaveResource($product));
    }
}

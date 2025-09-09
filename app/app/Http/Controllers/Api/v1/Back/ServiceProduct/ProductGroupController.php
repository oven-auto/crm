<?php

namespace App\Http\Controllers\Api\v1\Back\ServiceProduct;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceProduct\GroupCollection;
use App\Http\Resources\ServiceProduct\GroupSaveResource;
use App\Http\Requests\ServiceProduct\ProductGroupCreate;
use App\Http\Resources\Default\SuccessResource;
use App\Repositories\ServiceProduct\ProductGroupRepository;

class ProductGroupController extends Controller
{
    public function __construct(
        private ProductGroupRepository $repo,
        public $subject = 'Группа услуг',
        public $genus = 'female',
    )
    {
        $this->middleware('permission.developer:product_group_list')->only('index');
        $this->middleware('permission.developer:product_group_edit')->only('update');
        $this->middleware('permission.developer:product_group_delete')->only('delete');
        $this->middleware('permission.developer:product_group_show')->only('show');
        $this->middleware('permission.developer:product_group_add')->only('store');

        $this->middleware('notice.message')->only(['store', 'update', 'destroy',]);
    }

  
    
    public function index() : GroupCollection
    {
        $groups = $this->repo->get();

        return new GroupCollection($groups);
    }

    
    
    public function store(ProductGroupCreate $request) : GroupSaveResource
    {
        $group = $this->repo->create($request->all());

        return new GroupSaveResource($group);
    }

    
    
    public function show(int $id) : GroupSaveResource
    {
        $group = $this->repo->getById($id);

        return new GroupSaveResource($group);
    }

   
    
    public function update(int $id, ProductGroupCreate $request) : GroupSaveResource
    {
        $group = $this->repo->update($id, $request->all());

        return new GroupSaveResource($group);
    }

    
    
    public function destroy(int $id) : GroupSaveResource
    {
        $group = $this->repo->delete($id);

        return new GroupSaveResource($group);
    }
}

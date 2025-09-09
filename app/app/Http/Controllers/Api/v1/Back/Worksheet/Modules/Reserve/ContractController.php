<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Classes\Notice\Notice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Reserve\ContractSaveRequest;
use App\Http\Resources\Worksheet\Reserve\ContractResource;
use App\Models\WsmReserveNewCarContract;
use App\Repositories\Worksheet\Modules\Reserve\ReserveContractRepository;

class ContractController extends Controller
{
    public function __construct(
        private ReserveContractRepository $repo,
        public $genus = 'male',
        public $subject = 'Договор',
    )
    {
        $this->middleware('notice.message')->only(['store', 'update']);
    }



    public function store(WsmReserveNewCarContract $contract, ContractSaveRequest $request)
    {
        $this->repo->create($contract, $request->validated());

        return (new ContractResource($contract));
    }



    public function update(WsmReserveNewCarContract $contract, ContractSaveRequest $request)
    {
        $this->repo->update($contract, $request->validated());

        return (new ContractResource($contract));
    }



    public function show(WsmReserveNewCarContract $contract)
    {
        return new ContractResource($contract);
    }
}

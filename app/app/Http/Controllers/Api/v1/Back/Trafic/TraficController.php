<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Classes\Telegram\SystemMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trafic\TraficCreateRequest;
use Illuminate\Http\Request;
use App\Models\Trafic;
use App\Repositories\Trafic\TraficRepository;
use \App\Http\Resources\Trafic\TraficEditCollection;
use \App\Http\Resources\Trafic\TraficSaveResource;
use \App\Services\Comment\Comment;

class TraficController extends Controller
{
    public function __construct(
        public TraficRepository $repo,
        public $subject = 'Трафик',
        public $genus = 'male',
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'delete', 'close']);
    }



    public function index(Request $request)
    {
        $result = $this->repo->paginate($request->all());

        return new TraficEditCollection($result);
    }



    public function store(Trafic $trafic, TraficCreateRequest $request)
    {
        $this->repo->save($trafic, $request->getDTO());

        return (new TraficSaveResource($trafic));
    }



    public function update(Trafic $trafic,  TraficCreateRequest $request)
    {   
        $this->repo->save($trafic, $request->getDTO());

        return (new TraficSaveResource($trafic));
    }



    public function edit(Trafic $trafic)
    {
        if (!$trafic->isMy())
            Comment::add($trafic, 'show');
        
        return (new TraficSaveResource($trafic));
    }



    public function close(Trafic $trafic)
    {
        $this->repo->close($trafic);

        return (new TraficSaveResource($trafic));
    }



    public function delete(Trafic $trafic)
    {
        $this->repo->delete($trafic);

        return (new TraficSaveResource($trafic));
    }
}

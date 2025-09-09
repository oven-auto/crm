<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\FileRequest;
use App\Http\Resources\Client\File\IndexCollection;
use App\Http\Resources\Default\SuccessResource;
use App\Models\ClientFile;
use App\Repositories\Client\ClientFileRepository;
use App\Services\Comment\Comment;

class ClientFileController extends Controller
{
    public function __construct(
        private ClientFileRepository $repo,
        public $genus = 'male',
        public $subject = 'Фаил',    
    )
    {
        $this->middleware('notice.message')->only(['store', 'update', 'destroy']);
    }


    
    public function index(FileRequest $request)
    {
        $files = $this->repo->getByParam($request->all());

        return new IndexCollection($files);
    }


    
    public function store(ClientFile $file, FileRequest $request)
    {
        $files = $this->repo->save($request->input(), $request->allFiles());

        Comment::add($files->first(), 'create');

        return (new IndexCollection($files));
    }


    
    public function update(ClientFile $file, FileRequest $request)
    {
        $files = $this->repo->save($request->input(), $request->allFiles());

        Comment::add($file, 'update');

        return (new IndexCollection($files));
    }


    
    public function destroy(ClientFile $file)
    {
        Comment::add($file, 'delete');

        $file->delete();

        return new SuccessResource(1);
    }
}

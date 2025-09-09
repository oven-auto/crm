<?php

namespace App\Http\Controllers\Api\v1\Back\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserSaveResource;
use App\Http\Requests\User\UserCreate;

class UserController extends Controller
{
    private $service;
    public $genus = 'male';
    public $subject = 'Сотрудник';

    public function __construct(\App\Services\Auth\AuthService $service)
    {
        $this->service = $service;
        $this->middleware('notice.message')->only(['store', 'update', 'destroy']);
        $this->middleware('permission.developer:user_list')->only('index');
        $this->middleware('permission.developer:user_edit')->only('update');
        $this->middleware('permission.developer:user_delete')->only('delete');
        $this->middleware('permission.developer:user_show')->only('show');
        $this->middleware('permission.developer:user_add')->only('store');
    }

    

    public function index(Request $request)
    {
        $query = User::select('users.*')->with('role')->orderBy('lastname')->withTrashed();
        if($request->has('input') && $request->get('input')!='') {
            $query->where('lastname', 'LIKE', "%$request->input%");
            $query->leftJoin('roles', 'roles.id', 'users.role_id')->orWhere('roles.name', 'LIKE',  "%$request->input%");
        }

        $users = $query->get();

        return new UserCollection($users);
    }


    
    public function store(UserCreate $request)
    {
        $user = $this->service->register($request->input());

        return (new UserSaveResource($user));
    }


    
    public function show(User $user)
    {
        return new UserSaveResource($user);
    }


    
    
    public function update(User $user, UserCreate $request)
    {
        $user = $this->service->update($user, $request->input());

        return (new UserSaveResource($user));
    }

    
    
    public function destroy(User $user)
    {
        $old = $user->toArray();

        $user->delete();
        
        return (new UserSaveResource((object) $old));
    }
}

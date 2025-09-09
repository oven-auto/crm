<?php

namespace App\Http\Controllers\Api\v1\Back\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Resources\User\RoleCollection;
use App\Http\Resources\User\RoleSaveResource;

/**
 * CRUD контролер для ролей
 * хз имеет ли смысл делать сервис
 * методы контролера и так тонкие
 */
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.developer:role_list')->only('index');
        $this->middleware('permission.developer:role_edit')->only('update');
        $this->middleware('permission.developer:role_delete')->only('delete');
        $this->middleware('permission.developer:role_show')->only('show');
        $this->middleware('permission.developer:role_add')->only('store');
    }
    /**
     * Список ролей.
     *
     * @return RoleCollection
     */
    public function index() : RoleCollection
    {
        return new RoleCollection(Role::with('permissions')->get());
    }

    /**
     * Добавить роль.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RoleSaveResource
     */
    public function store(Role $role, Request $request) : RoleSaveResource
    {
        $role->fill($request->except('permissions'))->save();
        $role->permissions()->sync($request->permissions);
        return (new RoleSaveResource($role))->additional(['message' => 'Роль создана']);
    }

    /**
     * Показать роль.
     *
     * @param  Role $role
     * @return RoleSaveResource
     */
    public function show(Role $role) : RoleSaveResource
    {
        return (new RoleSaveResource($role));
    }

    /**
     * Изменить роль.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role $role
     * @return RoleSaveResource
     */
    public function update(Role $role, Request $request) : RoleSaveResource
    {
        $role->fill($request->except('permissions'))->save();
        $role->permissions()->sync($request->permissions);
        return (new RoleSaveResource($role))->additional(['message' => 'Роль изменена']);
    }

    /**
     * Удалить роль.
     *
     * @param  Role $role
     * @return RoleSaveResource
     */
    public function destroy(Role $role) : RoleSaveResource
    {
        Role::destroy($role->id);
        return (new RoleSaveResource($role))->additional(['message' => 'Роль удалена']);
    }
}

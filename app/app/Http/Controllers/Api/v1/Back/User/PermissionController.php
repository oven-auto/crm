<?php

namespace App\Http\Controllers\Api\v1\Back\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => \App\Models\Permission::select(['id','name'])->orderBy('name')->get(),
            'success' => 1
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) : \Illuminate\Http\JsonResponse
    {
        return response()->json([

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {
        return response()->json([

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) : \Illuminate\Http\JsonResponse
    {
        return response()->json([

        ]);
    }
}

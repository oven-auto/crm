<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $query = \App\Models\User::select('users.*');
        $structureIds = Auth::user()->structures->pluck('company_structure_id');

        if(Auth::user()->role_id == 0)
            $query->leftJoin('user_company_structures','user_company_structures.user_id','=','users.id')
                ->whereIn('user_company_structures.company_structure_id', $structureIds)
                ->groupBy('users.id');

        $users = $query->get();

        foreach($users as $item)
            $data[] = [
                'id' => $item->id,
                'name' => $item->cut_name
            ];

        return response()->json([
            'data' => $data,
            'success' => 1,
        ]);
    }
}

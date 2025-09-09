<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\FormOwner;
use Illuminate\Http\Request;

class FormOwnerController extends Controller
{
    public function index()
    {
        $forms = FormOwner::get();

        return response()->json([
            'data' => $forms,
            'success' => 1,
        ]);
    }
}

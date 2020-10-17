<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VueController extends Controller
{
    public function index()
    {
        $validator = Validator::make(request()->all(), [
            'p' => 'required',
            'q' => 'required|min:5|max:20',
        ]);

        if (request()->ajax()) {
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->getMessages(),
                ]);
            }
            return response()->json([
                'success' => true,
                'data' => ['ajax' => 'ajax']
            ]);
        }

        if ($validator->fails()) {
            return view('vue')->withErrors($validator);
        }

        return view('vue');
    }
}

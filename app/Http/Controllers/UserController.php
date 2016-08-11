<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

class UserController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAuth() {
        return Auth::user();
    }

    public function updateStatus(Request $request) {
    	if ($request->status) {
    		$user = Auth::user();
    		$user->status = $request->status;
    		$user->save();

    		return ['success' => true];
    	}

    	return ['success' => false];
    }
}

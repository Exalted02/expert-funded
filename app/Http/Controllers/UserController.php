<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
		$data = [];
		$data['list'] = User::where('status', '!=', 2)->get();
        return view('user.user-accounts', $data);
    }
}

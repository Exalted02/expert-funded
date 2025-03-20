<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
		$data = [];
		
        return view('client.dashboard', $data);
    }
    public function account()
    {
		$data = [];		
        return view('client.account', $data);
    }
    public function verification()
    {
		$data = [];		
        return view('client.verification', $data);
    }
    public function withdraw()
    {
		$data = [];		
        return view('client.withdraw', $data);
    }
}

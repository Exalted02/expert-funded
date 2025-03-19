<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
		$data = [];
		
        return view('dashboard', $data);
    }
}

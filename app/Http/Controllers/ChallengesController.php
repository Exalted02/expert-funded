<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ChallengesController extends Controller
{
    public function index()
    {
		$data = [];
		
        return view('user.challenges', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adjust_users_balance;
use DB;
use Carbon\Carbon;

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
    public function withdraw_request()
    {
		/*$balances = Adjust_users_balance::where('created_at', '>=', Carbon::now()->subDays(30))
			->where('type', 1)
			->where('status', 0)
			->get();
		// dd($sumAmounts);
		if($balances){
			
		}else{
			return response()->json([
				'success' => false,
				'message' => 'Not have any balance for withdraw.'
			]);
		}
		$data = [];		
        return view('client.withdraw', $data);*/
    }
}

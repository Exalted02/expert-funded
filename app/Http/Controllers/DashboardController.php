<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adjust_users_balance;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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
		$data['user']  = User::where('id', auth()->user()->id)->first();		
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
	
	public function update_client_account(Request $request)
	{
		$first_name = $request->first_name;
		$last_name = $request->last_name;
		$password = $request->password;
		$id = auth()->user()->id;
		
		$model = User::find($id);
		$model->first_name = $first_name;
		$model->last_name = $last_name;
		$model->name = $first_name.' '.$last_name;
		if(!empty($request->password))
		{
			$model->password = Hash::make($request->password);
		}
		$model->save();
		
		return response()->json(['message'=> 'Account updated successfully']);
	}
}

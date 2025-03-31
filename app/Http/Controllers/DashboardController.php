<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adjust_users_balance;
use App\Models\Client_payout_request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
	
    public function withdraw_request_amount(Request $request)
    {
		$get_records = Adjust_users_balance::where('user_id', Auth::id())
			->where('created_at', '<', Carbon::now()->subDays(30))
			->where('type', '!=', 0)
			->where('status', 0)
			->pluck('id');
		
		$get_records_amount = Adjust_users_balance::where('user_id', Auth::id())
			->where('created_at', '<', Carbon::now()->subDays(30))
			->where('type', '!=', 0)
			->where('status', 0)
			->sum('amount_paid');
			
		$data['get_records'] = implode(', ', $get_records->toArray());
		$data['get_records_amount'] = $get_records_amount;
		echo json_encode($data);
    }
    public function withdraw_submit(Request $request)
    {
		$payout = new Client_payout_request();
		$payout->user_id = Auth::id();
		$payout->requested_amount = $request->withdrawable_balance_input;
		$payout->withdrawable_adjust_id = $request->withdrawable_id;
		$payout->status = 0;
		if($payout->save()){
			$ids = explode(', ', $request->withdrawable_id);
			
			$update = Adjust_users_balance::whereIn('id', $ids)
				->update(['status' => 1]);
				
			$data['result'] ='success';	
		}else{
			$data['result'] ='error';
		}
		
		echo json_encode($data);
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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adjust_users_balance;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
		$data = [];
		$data['list'] = User::where('status', '!=', 2)->where('user_type', 1)->get();
        return view('user.user-accounts', $data);
    }
	public function update_data(Request $request)
	{
		$user = User::where('id', $request->id)->first();
		$data['result'] = $user;
		echo json_encode($data);
	}
	public function submit_data(Request $request)
	{
		$request->validate([
            'email' => 'required|email|unique:users,email,'.$request->id,
            'first_name' => 'required',
            'last_name' => 'required',
			'phone_number' => 'required|regex:/^[0-9]{10,15}$/',
        ]);
		
		$user = User::find($request->id);
		$user->email = $request->email;
		$user->name = $request->first_name.' '.$request->last_name;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->phone_number = $request->phone_number;
		if($user->save()){			
			return response()->json([
				'success' => true,
				'message' => 'User data updated successfully.'
			]);
		}else{
			return response()->json([
				'success' => false,
				'message' => 'User data not updated.'
			]);
		}
	}
	public function update_status(Request $request)
	{
		$status = User::where('id', $request->id)->first()->status;
		$change_status = $status == 1 ? 0 : 1;
		$update = User::where('id', $request->id)->update(['status'=> $change_status]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
	}
	public function get_delete_data(Request $request)
	{
		$user = User::where('id', $request->id)->first();
		$data['result'] = $user;
		echo json_encode($data);
	}
	public function final_delete_submit(Request $request)
	{
		$del = User::find($request->id);
		$del->status = 2;
		if($del->save()){
			$data['result'] ='success';
		}else{
			$data['result'] ='error';
		}		
		echo json_encode($data);
	}
	public function adjust_balance(Request $request)
	{
		$request->validate([
            'adjust_amount' => 'required',
        ],[
			'adjust_amount' => 'Amount is required.',
		]);
		
		$adj_balance = new Adjust_users_balance();
		$adj_balance->user_id = $request->adjust_amount_user;
		$adj_balance->amount_paid = $request->adjust_amount;
		if($request->type == 'add'){
			$adj_balance->type = 1;
		}else{
			$adj_balance->type = 0;
		}
		$adj_balance->status = 0;
		$adj_balance->save();
		
		$user = User::find($request->adjust_amount_user);
		if($request->type == 'add'){
			$user->users_balances = $user->users_balances + $request->adjust_amount;
		}else{
			$user->users_balances = $user->users_balances - $request->adjust_amount;
		}
		if($user->save()){
			$data['result'] ='success';
		}else{
			$data['result'] ='error';
		}		
		echo json_encode($data);
	}
	public function impersonateUser($id)
	{
		$user = User::find($id);

		if ($user) {
			Auth::loginUsingId($id);
			return redirect()->route('client.dashboard');
		}

		return redirect()->back()->with('error', 'User not found.');
	}
}

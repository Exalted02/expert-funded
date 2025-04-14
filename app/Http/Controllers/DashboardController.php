<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adjust_users_balance;
use App\Models\Client_payout_request;
use App\Models\Challenge;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	public function dashboard_challenge()
	{
		session()->forget('last_selected_challenge');

		$data = [];
		$challenges = Challenge::with(['get_challenge_type'])->where('user_id', Auth::id())->get();

		foreach ($challenges as $challenge) {
			$baseAmount = $challenge->get_challenge_type->amount;
			$baseDate = change_date_format($challenge->created_at, 'Y-m-d H:i:s', 'd-m');
			
			$adjustments = Adjust_users_balance::selectRaw("DATE_FORMAT(created_at, '%d-%m') as date_label, amount_paid")
				->where('user_id', Auth::id())
				->where('challenge_id', $challenge->id)
				->where('type', 1)
				->orderBy('created_at')
				->get();

			$values = collect([$baseAmount]);
			$runningTotal = $baseAmount;

			foreach ($adjustments as $entry) {
				$runningTotal += $entry->amount_paid;
				$values->push($runningTotal);
			}

			$challenge->chart_labels = collect([$baseDate])->merge($adjustments->pluck('date_label'));
			$challenge->chart_values = $values;

			// You can also pass min/max if you want to
			$min = $values->min();
			$max = $values->max();
			$range = $max - $min;
			$buffer = $range * 0.3;

			$challenge->y_min = floor($min - $buffer);
			$challenge->y_max = ceil($max + $buffer);
		}


		return view('client.dashboard-challenge', ['challenge' => $challenges]);
	}


    /*public function dashboard_challenge()
    {
		session()->forget('last_selected_challenge');
		
		$data = [];
		//For Equity
		$challenges  = Challenge::with(['get_challenge_type'])->where('user_id', Auth::id())->get();
		
		//$data['challenge']  = $challenge;
		foreach ($challenges as $challenge) {
			$initialAmount = (float) $challenge->get_challenge_type->amount;
			
			$entries = Adjust_users_balance::selectRaw("DATE_FORMAT(created_at, '%d-%m') as date_label, amount_paid")
				->where('user_id', Auth::id())
				->where('challenge_id', $challenge->id)
				->where('type', 1)
				->orderBy('created_at')
				->get();
				
			$challenge->chart_labels = $entries->pluck('date_label');
			$challenge->chart_values = $entries->pluck('amount_paid')->map(fn($v) => (float)$v);
		}
		// dd($challenges);
		return view('client.dashboard-challenge', ['challenge' => $challenges]);
        //return view('client.dashboard-challenge', $data);
    }*/
    public function index($id='')
    {
		session()->put('last_selected_challenge', $id);		
		// dd(session()->get('last_selected_challenge'));
		
		$data = [];
		$labels = [];
		$values = [];
		$previous_chart_amount = 0;
		//For Equity
		// $equity = Challenge::with(['get_challenge_type'])->where('user_id', Auth::id())->whereDate('created_at', Carbon::today())->get();
		$equity = Challenge::with(['get_challenge_type'])->where('id', $id)->where('user_id', Auth::id())->get();
		$equity_amount = $equity_percent = $initial_amount = $amount_paid_balance = $challenge_status = 0;
		foreach($equity as $equity_val){
			$adjust_users_balance = Adjust_users_balance::where('user_id', Auth::id())->where('challenge_id', $id)->where('type', 1)->sum('amount_paid');
			$equity_percent = ($adjust_users_balance / $equity_val->get_challenge_type->amount) * 100;
			// $equity_amount = $equity_amount + ($equity_val->amount_paid + $equity_val->get_challenge_type->amount);
			$equity_amount = $equity_amount + ($adjust_users_balance + $equity_val->get_challenge_type->amount);
			$initial_amount = $initial_amount + $equity_val->get_challenge_type->amount;
			// $amount_paid_balance = $amount_paid_balance + $equity_val->amount_paid;
			$amount_paid_balance = $adjust_users_balance;
			$challenge_status = $equity_val->status;
			
			$labels[] = change_date_format($equity_val->created_at, 'Y-m-d H:i:s', 'd-m-Y');
			$previous_chart_amount = $equity_val->get_challenge_type->amount;
			$values[] = $previous_chart_amount;
		}
		
		//For eligible withdraw
		$eligible_withdraw = Adjust_users_balance::where('user_id', Auth::id())
					->where('created_at', '<', Carbon::now()->subDays(30))
					// ->where('type', '!=', 0)
					// ->where('status', '!=', 2)
					->where('type', 2)
					->where('status', 2)
					->sum('amount_paid');
		
		//For chart
		$entries = Adjust_users_balance::selectRaw("DATE_FORMAT(created_at, '%d-%m-%Y') as date_label, amount_paid")
		->where('user_id', Auth::id())
		->where('type', 1)
        ->where('challenge_id', $id)
        ->orderBy('created_at')
        ->get();

		

		foreach ($entries as $entry) {
			$labels[] = $entry->date_label;
			// $values[] = abs((float) $entry->amount_paid);
			$previous_chart_amount = $previous_chart_amount +  (float) $entry->amount_paid;
			$values[] = $previous_chart_amount;
		}
		// dd($values);
		
		$data['equity_amount']  = $equity_amount;
		$data['equity_percent']  = $equity_percent;
		$data['initial_amount']  = $initial_amount;
		$data['amount_paid_balance']  = $amount_paid_balance;
		$data['eligible_withdraw']  = $equity_amount - $eligible_withdraw;
		// $data['total_balance']  = $equity_amount + $eligible_withdraw;
		$data['total_balance']  = $equity_amount;		
		$data['challenge_status']  = $challenge_status;
		
		$data['chartLabels']  = $labels;
		$data['chartData']  = $values;
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
		$totalDays = Carbon::now()->daysInMonth;
		$currentDay = Carbon::now()->day;
		$eligibleDate = Carbon::now()->addDays(30);
		
		$data['total_day']  = $totalDays;
		$data['current_day']  = $currentDay;
		$data['current_day']  = $currentDay;
		$data['eligible_date']  = $eligibleDate->toDateString();
        return view('client.withdraw', $data);
    }
	
    public function withdraw_request_amount(Request $request)
    {
		$get_records = Adjust_users_balance::where('user_id', Auth::id())
			->where('created_at', '<', Carbon::now()->subDays(30))
			->where('type', 1)
			// ->where('type', '!=', 0)
			->where('status', 0)
			->pluck('id');
		
		$get_records_amount = Adjust_users_balance::where('user_id', Auth::id())
			->where('created_at', '<', Carbon::now()->subDays(30))
			->where('type', 1)
			// ->where('type', '!=', 0)
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

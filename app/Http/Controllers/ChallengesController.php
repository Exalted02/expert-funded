<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Challenge;
use App\Models\Challenge_type;
use App\Models\Adjust_users_balance;
use Illuminate\Support\Facades\Hash;

class ChallengesController extends Controller
{
    public function index()
    {
		$data = [];
		$data['list'] = Challenge::with(['get_challenge_type'])->get();
		// dd($data['list']);
		$data['c_list'] = Challenge_type::where('status', 1)->get();
        return view('user.challenges', $data);
    }
    public function check_email(Request $request)
    {
		$request->validate([
            'trader_email' => 'required|email'
        ]);
		
		$user = User::where('email', $request->post('trader_email'))->first();
		if($user){
			return response()->json([
				'success' => true,
				'message' => 'Email is exists.',
				'data' => [
					'first_name' => $user->first_name ?? '',
					'last_name' => $user->last_name ?? '',
					'phone_number' => $user->phone_number ?? '',
				]
			]);
		}else{
			return response()->json([
				'success' => true,
				'message' => 'Email not exists.',
				'data' => []
			]);
		}		
    }
    public function trader_challenge_amount(Request $request)
    {
		$challenge_type = Challenge_type::where('id', $request->post('id'))->first();
		if($challenge_type){
			if($challenge_type->percent == '' || $challenge_type->percent == null){
				$amount = $challenge_type->amount_paid;
			}else{
				$amount = $challenge_type->amount*($challenge_type->percent/100);
			}
		}else{
			$amount = 0;
		}
		return response()->json([
			'success' => true,
			'amount' => $amount,
		]);	
    }
    public function challenge_submit(Request $request)
    {
		$request->validate([
            'traders_email' => 'required|email',
            'trader_first_name' => 'required',
            'trader_last_name' => 'required',
			'trader_phone_number' => 'nullable|regex:/^[0-9]{10,15}$/',
            'trader_challenge' => 'required',
			'trading_amount' => 'required|numeric|min:1', // Ensure it's a number and greater than zero
			'trading_document' => 'nullable|file|mimes:jpg,png,pdf|max:2048', // File validation
        ]);
		
		$user = User::where('email', $request->post('traders_email'))->first();
		$password = '12345678';
		if($user){
			$user_id = $user->id;
			$user->users_balances = $user->users_balances + $request->post('trading_amount');
			$user->save();
		}else{
			$model = new User();
			$model->email = $request->post('traders_email');
			$model->name = $request->post('trader_first_name').' '.$request->post('trader_last_name');
			$model->first_name = $request->post('trader_first_name');
			$model->last_name = $request->post('trader_last_name');
			$model->phone_number = $request->post('trader_phone_number');
			$model->password = Hash::make($password);
			$model->users_balances = $request->post('trading_amount');
			$model->status = 1;
			$model->email_verified_at = date('Y-m-d h:i:s');
			$model->created_at = date('Y-m-d h:i:s');
			
			if($model->save()){
				$client_name = $model->first_name." ".$model->last_name;
				$APP_NAME  = env('APP_NAME');
				$logo = '<img src="' . url('front-assets/img/-logo1.jpg') . '" alt="Expert funded" width="150">';
				$email_content = get_email(1);
				if(!empty($email_content))
				{
					$maildata = [
						'subject' => $email_content->message_subject,
						'body' => str_replace(array("[LOGO]", "[NAME]", "[SCREEN_NAME]", "[EMAIL]", "[PASSWORD]", "[YEAR]", "[LINK_DASHBOARD]"), array($logo, $client_name, $APP_NAME, $model->email, $password, date('Y'), route('client.dashboard-challenge')), $email_content->message),
						'toEmails' => array($model->email),
					];
					try {
						send_email($maildata);
					} catch (\Exception $e) {
						//
					}
				}
			}
			
			$user_id = $model->id;
		}
		$fileName = '';
		if($request->hasFile('trading_document')) {
			$destinationPath = public_path('uploads/challenges/'. $user_id);
			if (!file_exists($destinationPath)) {
				mkdir($destinationPath, 0777, true);
			}
			$file = $request->file('trading_document');
			$fileName = time() . '_' . $file->getClientOriginalName();
			$file->move($destinationPath, $fileName);
		}
		$challenge = new Challenge();
		$challenge->user_id = $user_id;
		$challenge->email = $request->post('traders_email');
		$challenge->first_name = $request->post('trader_first_name');
		$challenge->last_name = $request->post('trader_last_name');
		$challenge->phone = $request->post('trader_phone_number');
		$challenge->challenge_id = $request->post('trader_challenge');
		$challenge->amount_paid = $request->post('trading_amount');
		$challenge->proof_document = $fileName;
		$challenge->comment = $request->post('comment');
		$challenge->status = 0;
		$challenge->created_at = date('Y-m-d h:i:s');
		if($challenge->save()){
		
			$adj_balance = new Adjust_users_balance();
			$adj_balance->user_id = $user_id;
			$adj_balance->challenge_id = $challenge->id;
			$adj_balance->amount_paid = $request->post('trading_amount');
			$adj_balance->type = 2;
			$adj_balance->status = 0;
			$adj_balance->save();
			
			return response()->json([
				'success' => true,
				'message' => 'Challenge created successfully.'
			]);
		}else{
			return response()->json([
				'success' => false,
				'message' => 'Challenge not created.'
			]);
		}
    }
    public function challenge_details(Request $request)
    {
		$data = [];
		$challenge = Challenge::with(['get_challenge_type'])->where('id', $request->id)->first();
		$html = '';
		$html .= '<ul class="personal-info">
					<li>
						<div class="title">Trader`s Email:</div>
						<div class="text">'. $challenge->email .'</div>
					</li>
					<li>
						<div class="title">Trader`s First Name:</div>
						<div class="text">'. $challenge->first_name .'</div>
					</li>
					<li>
						<div class="title">Trader`s Last Name:</div>
						<div class="text">'. $challenge->last_name .'</div>
					</li>';
					if($challenge->phone != null || $challenge->phone != ''){
						$html .= '<li>
							<div class="title">Trader`s Phone:</div>
							<div class="text">'. $challenge->phone .'</div>
						</li>';
					}
					$html .= '<li>
						<div class="title">Challenge:</div>
						<div class="text">'. $challenge->get_challenge_type->title .'</div>
					</li>
					<li>
						<div class="title">Amount Paid:</div>
						<div class="text">'. $challenge->amount_paid .'</div>
					</li>';
					if($challenge->comment != null || $challenge->comment != ''){
						$html .= '<li>
							<div class="title">Comment:</div>
							<div class="text">'. $challenge->comment .'</div>
						</li>';
					}
					if($challenge->proof_document != null || $challenge->proof_document != ''){
					$proof_document = asset('uploads/challenges/'.$challenge->user_id.'/'.$challenge->proof_document);
					$html .= '<li>
						<div class="title">Downloadable Documents:</div>
						<div class="text"><a id="view_back" class="btn btn-sm w-100 btn-info rounded-pill" href="'.$proof_document.'" download="'.$challenge->proof_document.'"><i class="la la-eye"></i> View</a></div>
					</li>';
					}
				$html .= '</ul>';
		return response()->json([
			'html' => $html
		]);
	}
    public function update_status(Request $request)
    {
		$change_status = $request->type_val;
		
		$update = Challenge::where('id', $request->id)->update(['status'=> $change_status]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
    }
    public function challenge_ajax_details(Request $request)
    {
		$challenge = Challenge::with(['get_challenge_type'])->where('id', $request->post('id'))->first();
		$adjust_users_balance = Adjust_users_balance::where('challenge_id', $request->post('id'))->where('type', 1)->sum('amount_paid');
		$data['result'] = $challenge;
		$data['adjust_users_balance'] = $adjust_users_balance;
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
		$adj_balance->challenge_id = $request->adjust_amount_challenge;
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
	public function multi_adjust_balance(Request $request)
	{
		$request->validate([
            'adjust_percent' => 'required',
        ],[
			'adjust_percent' => 'Percent is required.',
		]);
		
		$cat_ids = explode(',',$request->challenge_id);
		foreach($cat_ids as $k=>$id_val){
			$challenge = Challenge::with(['get_challenge_type'])->where('id', $id_val)->first();
			
			$user = User::find($challenge->user_id);
			if($challenge->get_challenge_type->amount > 0){
				$percentage_value = $challenge->get_challenge_type->amount * ($request->adjust_percent/100);
				
				$adj_balance = new Adjust_users_balance();
				$adj_balance->user_id = $challenge->user_id;
				$adj_balance->challenge_id = $id_val;
				$adj_balance->amount_paid = $percentage_value;
				$adj_balance->percentage_value = $request->adjust_percent;
				if($request->type_val == 'add'){
					$adj_balance->type = 1;
				}else{
					$adj_balance->type = 0;
				}
				$adj_balance->status = 0;
				$adj_balance->save();
			
				if($request->type_val == 'add'){
					$user->users_balances = $user->users_balances + $percentage_value;
				}else{
					$user->users_balances = $user->users_balances - $percentage_value;
				}
				$user->save();
			}
		}
		$data['result'] ='success';
		echo json_encode($data);
	}
}

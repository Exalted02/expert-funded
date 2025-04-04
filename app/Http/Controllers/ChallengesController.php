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
		$data['list'] = Challenge::with(['get_challenge_type'])->where('status', '!=', 2)->get();
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
						'body' => str_replace(array("[LOGO]", "[NAME]", "[SCREEN_NAME]", "[EMAIL]", "[PASSWORD]", "[YEAR]", "[LINK_DASHBOARD]"), array($logo, $client_name, $APP_NAME, $model->email, $password, date('Y'), route('client.dashboard')), $email_content->message),
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
		$model = new Challenge();
		$model->user_id = $user_id;
		$model->email = $request->post('traders_email');
		$model->first_name = $request->post('trader_first_name');
		$model->last_name = $request->post('trader_last_name');
		$model->phone = $request->post('trader_phone_number');
		$model->challenge_id = $request->post('trader_challenge');
		$model->amount_paid = $request->post('trading_amount');
		$model->proof_document = $fileName;
		$model->comment = $request->post('comment');
		$model->status = 1;
		$model->created_at = date('Y-m-d h:i:s');
		if($model->save()){
		
			$adj_balance = new Adjust_users_balance();
			$adj_balance->user_id = $user_id;
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
}

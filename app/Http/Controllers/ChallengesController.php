<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Challenge;
use App\Models\Challenge_type;
use App\Models\Adjust_users_balance;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ChallengeImport;

use Illuminate\Support\Str;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ChallengesController extends Controller
{
    public function index(Request $request)
    {
		$data = [];
		
		$data['search_status'] = '';
		$dataArr = Challenge::with(['get_challenge_type']);
		if($request->has('search_status') && $request->search_status !== '' && isset($request->search_status))
		{
			$dataArr->where('status', $request->search_status);
			$data['search_status'] = $request->search_status;
		}
		
		$data['list'] = $dataArr->get();
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
		$password = Str::random(8);
		$APP_NAME  = env('APP_NAME');
		$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
		
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
		$trading_password = '';
		$length = 10;

		for ($i = 0; $i < $length; $i++) {
			$trading_password .= $characters[random_int(0, strlen($characters) - 1)];
		}
		$trading_id = mt_rand(10000000, 99999999);
		
		if($user){
			$user_id = $user->id;
			$user_email = $user->email;
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
			$model->trading_account_id = $trading_id;
			$model->trading_account_pw = $trading_password;
			$model->email_verified_at = date('Y-m-d h:i:s');
			$model->created_at = date('Y-m-d h:i:s');
			
			if($model->save()){
				$client_name = $model->first_name." ".$model->last_name;
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
			
			$user_email = $model->email;
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
		$challenge->client_id = $trading_id;
		$challenge->client_pw = $trading_password;
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
			$adj_balance->exact_amount_paid = $request->post('trading_amount');
			$adj_balance->amount_paid = $request->post('trading_amount');
			$adj_balance->type = 2;
			$adj_balance->status = 0;
			$adj_balance->save();
			
			//For trading account
			$email_content1 = get_email(2);
			if(!empty($email_content1))
			{
				$challenge_type = Challenge_type::where('id', $request->post('trader_challenge'))->first();
				$maildata1 = [
					'subject' => $email_content1->message_subject,
					'body' => str_replace(array("[LOGO]", "[PRICE_SIZE]", "[PRICE]", "[DATE]", "[SCREEN_NAME]", "[YEAR]", "[LINK_LOGIN]"), array($logo, get_currency_symbol().$challenge_type->amount, get_currency_symbol().$request->post('trading_amount'), date('d M y'), $APP_NAME, date('Y'), route('login')), $email_content1->message),
					'toEmails' => array($user_email),
				];
				try {
					send_email($maildata1);
				} catch (\Exception $e) {
					//
				}
			}
			
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
		
		if($change_status == 1){
			$up = ['funded_date'=> date('Y-m-d'), 'status'=> $change_status];
		}else{
			$up = ['status'=> $change_status];
		}
		$update = Challenge::where('id', $request->id)->update($up);
		
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
	public function generateCertificate($challenge_id, $name, $amount, $date)
	{
		$amount = number_format($amount, 2, '.', ',').get_currency_symbol();
		$date = change_date_format($date, 'Y-m-d', 'd M y');

		// Load image
		$manager = new ImageManager(new Driver());
		$image = $manager->read(public_path('certificate/certificate.png')); // your certificate background
		$imageWidth = $image->width();
		// Font path
		$fontPath = public_path('fonts/arialbi.ttf');

		// Add Name
		$image->text($name, $imageWidth / env('CERTIFICATE_NAME_HORIZONTAL'), env('CERTIFICATE_NAME_VERTICAL'), function ($font) {
			$font->filename(public_path('fonts/arialbi.ttf'));
			$font->size(80);
			$font->color('#ffffff');
			$font->align('center');
			$font->valign('middle');
			$font->angle(0);
		});

		// Add Profit
		/*$image->text($amount, $imageWidth / 2, 680, function ($font) {
			$font->filename($fontPath);
			$font->size(100);
			$font->color('#ffffff');
			$font->align('center');
			$font->valign('middle');
			$font->angle(0);
		});*/

		// Add Date
		$image->text($date, $imageWidth - env('CERTIFICATE_DATE_HORIZONTAL'), env('CERTIFICATE_DATE_VERTICAL'), function ($font) {
			$font->filename(public_path('fonts/arialbd.ttf'));
			$font->size(25);
			$font->color('#ffffff');
			$font->align('right');
			$font->valign('bottom');
			$font->angle(0);
		});

		// Save or return response
		$imagePath = public_path('certificate/'.$challenge_id.'.png');
		$image->save($imagePath);
		return $imagePath;
		return response()->download($imagePath);
	}
	public function adjust_balance(Request $request)
	{			
		$request->validate([
            'adjust_amount' => 'required',
        ],[
			'adjust_amount' => 'Percent is required.',
		]);
		//$adjustAmount = floatval($request->adjust_amount);
		$challenge = Challenge::with(['get_challenge_type'])->where('id', $request->adjust_amount_challenge)->first();
		$percentage_value = $challenge->get_challenge_type->amount * ($request->adjust_amount/100);
		
		$adj_balance = new Adjust_users_balance();
		$adj_balance->user_id = $request->adjust_amount_user;
		$adj_balance->challenge_id = $request->adjust_amount_challenge;
		$adj_balance->exact_amount_paid = $percentage_value;
		$adj_balance->amount_paid = $percentage_value;
		$adj_balance->percentage_value = $request->adjust_amount;
		if($request->type == 'add'){
			$adj_balance->type = 1;
		}else{
			$adj_balance->type = 0;
		}
		$adj_balance->status = 0;
		$adj_balance->save();
		
		$user = User::find($request->adjust_amount_user);
		if($request->type == 'add'){
			$user->users_balances += $percentage_value;
		}else{
			$user->users_balances = $percentage_value;
		}
		
		$APP_NAME  = env('APP_NAME');
		$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
		//Update status to failed
			$maximum_drawdown = Challenge::with(['get_challenge_type'])->where('id', $request->adjust_amount_challenge)->first();
			if($maximum_drawdown->status != 2){ //If status not failed
				$adjust_users_balance = Adjust_users_balance::where('challenge_id', $request->adjust_amount_challenge)->where('type', 1)->sum('amount_paid');
				
				$maximum_drawdown_amount = $maximum_drawdown->get_challenge_type->amount * (10/100);				
				if ($adjust_users_balance <= -$maximum_drawdown_amount) {
					$maximum_drawdown->status = 2;
					$maximum_drawdown->funded_date = null;
					$maximum_drawdown->save();
				}
				
				$maximum_daily_drawdown_amount = ($adjust_users_balance + $maximum_drawdown->get_challenge_type->amount) * (5/100);
				if ($adjust_users_balance <= -$maximum_daily_drawdown_amount) {
					$maximum_drawdown->status = 2;
					$maximum_drawdown->funded_date = null;
					$maximum_drawdown->save();
				}
			}
		//Update status to failed
		//Update status to funded
			$get_challenge = Challenge::with(['get_challenge_type'])->where('id', $request->adjust_amount_challenge)->first();
			if($get_challenge->status != 1){
				$adjust_users_balance = Adjust_users_balance::where('challenge_id', $request->adjust_amount_challenge)->where('type', 1)->sum('amount_paid');
				
				$achieved_balance = $get_challenge->get_challenge_type->amount * (10/100);
				if($achieved_balance <= $adjust_users_balance){
					$get_challenge->status = 1;
					$get_challenge->funded_date = date('Y-m-d');
					$get_challenge->save();
					
					//Update all adjust balance to zero
					$adjust_users_balance_zero = Adjust_users_balance::where('challenge_id', $request->adjust_amount_challenge)->where('type', 1)->update(['amount_paid' => 0]);
							
					$phase = $get_challenge->get_challenge_type->title;
					$email_content = get_email(4);
					if(!empty($email_content))
					{
						$maildata = [
							'subject' => $email_content->message_subject,
							'body' => str_replace(array("[LOGO]", "[PHASE]", "[SCREEN_NAME]", "[YEAR]"), array($logo, $phase, $APP_NAME, date('Y')), $email_content->message),
							'toEmails' => array($get_challenge->email),
							//'files' => array($attatchment),
						];
						
						if(env('CERTIFICATE_SEND')){
							$name = $get_challenge->first_name.' '.$get_challenge->last_name;
							$attatchment = $this->generateCertificate($request->adjust_amount_challenge, $name, $adjust_users_balance, $get_challenge->funded_date);
							//dd($attatchment);
							if(!empty($attatchment)) {
								$maildata['files'] = [$attatchment];
							}
						}
						
						try {
							send_email($maildata);
							
						} catch (\Exception $e) {
							//
						}
					}
				}
			}
		//Update status to funded
		
		//Email for positive amount
		if($request->adjust_amount > 0){
			$email_content = get_email(8);
			if(!empty($email_content))
			{
				$maildata = [
					'subject' => $email_content->message_subject,
					'body' => str_replace(array("[LOGO]", "[PERCENT_VALUE]", "[SCREEN_NAME]", "[YEAR]"), array($logo, $request->adjust_amount, $APP_NAME, date('Y')), $email_content->message),
					'toEmails' => array($get_challenge->email),
				];
				try {
					send_email($maildata);
				} catch (\Exception $e) {
					//
				}
			}
		}
		//Email for positive amount
		
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
				$adj_balance->exact_amount_paid = $percentage_value;
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
				$APP_NAME  = env('APP_NAME');
				$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
				//Update status to failed
					$maximum_drawdown = Challenge::with(['get_challenge_type'])->where('id', $id_val)->first();
					if($maximum_drawdown->status != 2){ //If status not failed
						$adjust_users_balance = Adjust_users_balance::where('challenge_id', $id_val)->where('type', 1)->sum('amount_paid');
						
						$maximum_drawdown_amount = $maximum_drawdown->get_challenge_type->amount * (10/100);				
						if ($adjust_users_balance <= -$maximum_drawdown_amount) {
							$maximum_drawdown->status = 2;
							$maximum_drawdown->funded_date = null;
							$maximum_drawdown->save();
						}
						
						$maximum_daily_drawdown_amount = ($adjust_users_balance + $maximum_drawdown->get_challenge_type->amount) * (5/100);
						if ($adjust_users_balance <= -$maximum_daily_drawdown_amount) {
							$maximum_drawdown->status = 2;
							$maximum_drawdown->funded_date = null;
							$maximum_drawdown->save();
						}
					}
				//Update status to failed
				//Update status to funded
					$get_challenge = Challenge::with(['get_challenge_type'])->where('id', $id_val)->first();
					if($get_challenge->status != 1){
						$adjust_users_balance = Adjust_users_balance::where('challenge_id', $id_val)->where('type', 1)->sum('amount_paid');
						
						$achieved_balance = $get_challenge->get_challenge_type->amount * (10/100);
						if($achieved_balance <= $adjust_users_balance){
							$get_challenge->status = 1;
							$get_challenge->funded_date = date('Y-m-d');
							$get_challenge->save();
							
							//Update all adjust balance to zero
							$adjust_users_balance_zero = Adjust_users_balance::where('challenge_id', $id_val)->where('type', 1)->update(['amount_paid' => 0]);
							
							$phase = $get_challenge->get_challenge_type->title;
							$email_content = get_email(4);
							if(!empty($email_content))
							{
								$maildata = [
									'subject' => $email_content->message_subject,
									'body' => str_replace(array("[LOGO]", "[PHASE]", "[SCREEN_NAME]", "[YEAR]"), array($logo, $phase, $APP_NAME, date('Y')), $email_content->message),
									'toEmails' => array($get_challenge->email),
									//'files' => array($attatchment),
								];
								if(env('CERTIFICATE_SEND')){
									$name = $get_challenge->first_name.' '.$get_challenge->last_name;
									$attatchment = $this->generateCertificate($request->adjust_amount_challenge, $name, $adjust_users_balance, $get_challenge->funded_date);
									//dd($attatchment);
									if(!empty($attatchment)) {
										$maildata['files'] = [$attatchment];
									}
								}
								try {
									send_email($maildata);
									
								} catch (\Exception $e) {
									//
								}
							}
						}
					}
				//Update status to funded
				
				//Email for positive amount
					if($request->adjust_percent > 0){
						$email_content = get_email(8);
						if(!empty($email_content))
						{
							$maildata = [
								'subject' => $email_content->message_subject,
								'body' => str_replace(array("[LOGO]", "[PERCENT_VALUE]", "[SCREEN_NAME]", "[YEAR]"), array($logo, $request->adjust_percent, $APP_NAME, date('Y')), $email_content->message),
								'toEmails' => array($get_challenge->email),
							];
							try {
								send_email($maildata);
							} catch (\Exception $e) {
								//
							}
						}
					}
				//Email for positive amount
			}
		}
		$data['result'] ='success';
		echo json_encode($data);
	}
	public function challenge_import_submit(Request $request)
	{
		ini_set('max_execution_time', 300); // 5 minutes
		ini_set('memory_limit', '512M');

		$request->validate([
            'import_excel' => 'required|mimes:xlsx,xls,csv'
        ]);
		
		//Excel::import(new ChallengeImport, $request->file('import_excel'));
		$csv_data = Excel::toArray(new ChallengeImport, $request->file('import_excel'));
		if(count($csv_data[0]) > 0)  {
			foreach($csv_data[0] as $row){
				if($row['email'] != ''){
					$user = User::where('email', $row['email'])->first();
					$password = Str::random(8);
					
					//Name start
					$full_name = $row['full_name'];
					$name_parts = explode(' ', $full_name);			
					$first_name = $name_parts[0]; // Get first name			
					$last_name = isset($name_parts[1]) ? implode(' ', array_slice($name_parts, 1)) : ''; // Get last name (everything else after first)
					
					//Amount start
					$amount = $row['amount'];
					$clean_amount = str_replace([',', '$'], '', $amount);
					
					$value = $row['phase'];
					preg_match('/(\d+)K/', $value, $matches);
					$number = isset($matches[1]) ? (int)$matches[1] * 1000 : 0;			
					
					//phase start
					$phase = $row['phase'];
					$challenge_type = Challenge_type::where('title', $phase)->first();
					if($challenge_type){
						$user_balance = $challenge_type->amount_paid;
						
						$challenge_type_id = $challenge_type->id;
					}else{
						$c_type = new Challenge_type();
						$c_type->title = $phase;
						$c_type->amount = $number;
						$c_type->amount_paid = 0;
						$c_type->status = 1;
						$c_type->status = 1;
						$c_type->created_at = date('Y-m-d H:i:s');
						$c_type->save();
						
						$user_balance = 0;
						$challenge_type_id = $c_type->id;
					}
					
					$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
					$trading_password = '';
					$length = 10;

					for ($i = 0; $i < $length; $i++) {
						$trading_password .= $characters[random_int(0, strlen($characters) - 1)];
					}
					$trading_id = mt_rand(10000000, 99999999);

					if($user){
						$user_id = $user->id;
						$user->users_balances = $user->users_balances + $user_balance;
						$user->save();
					}else{
						$model = new User();
						$model->email = $row['email'];
						$model->name = $row['full_name'];
						$model->first_name = $first_name;
						$model->last_name = $last_name;
						$model->password = Hash::make($password);
						$model->users_balances = $user_balance;
						$model->status = 1;
						$model->trading_account_id = $trading_id;
						$model->trading_account_pw = $trading_password;
						$model->email_verified_at = date('Y-m-d h:i:s');
						$model->created_at = date('Y-m-d h:i:s');
						
						if($model->save()){
							/*$client_name = $model->first_name." ".$model->last_name;
							$APP_NAME  = env('APP_NAME');
							$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="Expert funded" width="150">';
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
							}*/
						}
						
						$user_id = $model->id;
					}
					
					if($row['status'] == 'ON_CHALLENGE'){
						$challenge_status = 0;
					}else if($row['status'] == 'FUNDED'){
						$challenge_status = 1;
					}else{
						$challenge_status = 2;
					}
					
					$challenge=new Challenge();
					$challenge->client_id = $row['id_klienta'];
					$challenge->client_pw = $trading_password;
					$challenge->user_id = $user_id;
					$challenge->email = $row['email'];
					$challenge->first_name = $first_name;
					$challenge->last_name = $last_name;
					$challenge->challenge_id = $challenge_type_id;
					$challenge->amount_paid = 0;
					$challenge->status = $challenge_status;
					if($challenge_status == 1){
						$challenge->funded_date = date('Y-m-d');
					}
					$challenge->created_at = date('Y-m-d h:i:s');
					$challenge->save();
					
					$adj_balance = new Adjust_users_balance();
					$adj_balance->user_id = $user_id;
					$adj_balance->challenge_id = $challenge->id;
					$adj_balance->exact_amount_paid = 0;
					$adj_balance->amount_paid = 0;
					$adj_balance->type = 2;
					$adj_balance->status = 0;
					$adj_balance->save();
					
					if($clean_amount != $number){
						$adj_balance1 = new Adjust_users_balance();
						$adj_balance1->user_id = $user_id;
						$adj_balance1->challenge_id = $challenge->id;
						$adj_balance1->exact_amount_paid = $clean_amount - $number;
						$adj_balance1->amount_paid = $clean_amount - $number;
						$adj_balance1->type = 1;
						$adj_balance1->status = 0;
						$adj_balance1->save();
					}
				}
			}
		}
		
		return redirect()->back()->with('success', 'Challenge imported successfully!');
	}
}

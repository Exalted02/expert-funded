<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kyc_documents;

class KycController extends Controller
{
    public function index()
    {
		$data = [];
		$data['documents'] = Kyc_documents::with('get_client')->get();
		
        return view('user.kyc', $data);
    }
	public function kyc_document(Request $request)
	{
		$data = [];
		$id = $request->id;
		$documents = Kyc_documents::with('get_client')->where('id', $id)->first();
		
		return response()->json([
			'documents_details' => $documents,
			'forntal_path' => asset('uploads/kyc/'. $documents->get_client->id .'/frontal'),
			'back_path' => asset('uploads/kyc/'. $documents->get_client->id .'/back'),
			'residence_path' => asset('uploads/kyc/'. $documents->get_client->id .'/residence')
		]);
		
	}
	public function kyc_document_status_update(Request $request)
	{
		$id = $request->id;
		$status_typ = $request->status_typ;
		$client_dtls = Kyc_documents::with('get_client')->where('id', $id)->first();
		$client_name = $client_dtls->get_client->first_name.' '.$client_dtls->get_client->first_last;
		$APP_NAME  = env('APP_NAME');
		
		if($status_typ == 'reject')
		{
			$model = Kyc_documents::find($id);
			$model->status = 0;
			$model->save();
			// send mail to client 
			//$logo = asset('front-assets/img/-logo1.jpg');
			//$logo = '<img src="'. asset('front-assets/img/-logo1.jpg') .'">';
			$logo = '<img src="' . url('front-assets/img/-logo1.jpg') . '" alt="Expert funded" width="150">';

			$email_content = get_email(7);
			if(!empty($email_content))
			{
				$maildata = [
					'subject' => $email_content->message_subject,
					'body' => str_replace(array("[LOGO]", "[SCREEN_NAME]"), array($logo,$APP_NAME), $email_content->message),
					'toEmails' => array($client_dtls->get_client->email),
				];
				
				try {
					send_email($maildata);
					$this->info("Email sent to: {$client_dtls->get_client->email}");
				} catch (\Exception $e) {
					\Log::error("Failed to send email to {$client_dtls->get_client->email}: {$e->getMessage()}");
					
				}
			}
		}
		
		if($status_typ == 'accept')
		{
			$model = Kyc_documents::find($id);
			$model->status = 2;
			$model->save();
			// send mail to client 
			$logo = '<img src="' . url('front-assets/img/-logo1.jpg') . '" alt="Expert funded" width="150">';
			$email_content = get_email(5);
			if(!empty($email_content))
			{
				$maildata = [
					'subject' => $email_content->message_subject,
					'body' => str_replace(array("[LOGO]", "[SCREEN_NAME]"), array($logo, $APP_NAME), $email_content->message),
					'toEmails' => array($client_dtls->get_client->email),
				];
				
				try {
					send_email($maildata);
					$this->info("Email sent to: {$client_dtls->get_client->email}");
				} catch (\Exception $e) {
					\Log::error("Failed to send email to {$client_dtls->get_client->email}: {$e->getMessage()}");
					
				}
			}
		}
		
		$changeStatus = Kyc_documents::where('id',$id )->first()->status;
		return response()->json(['change_status'=> $changeStatus]);
	}
}

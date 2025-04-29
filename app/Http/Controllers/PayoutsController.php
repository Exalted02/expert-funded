<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client_payout_request;
use App\Models\User;

class PayoutsController extends Controller
{
    public function index()
    {
		$data = [];
		$data['pending_payout'] = Client_payout_request::where('status', 0)->count();
		$data['accepted_payout'] = Client_payout_request::where('status', 1)->count();
		$data['rejected_payout'] = Client_payout_request::where('status', 2)->count();
		$data['list'] = Client_payout_request::with(['get_user_details'])->get();
		// dd($data['list']);
        return view('user.payouts', $data);
    }
    public function update_status(Request $request)
    {
		$change_status = $request->type_val;
		
		$update = Client_payout_request::where('id', $request->status_user)->update(['status'=> $change_status, 'reason'=> $request->reason]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
    }
    public function multi_update_status(Request $request)
    {
		$cat_ids = explode(',',$request->users_id);
		$change_status = $request->type_val;
		
		$update = Client_payout_request::whereIn('id', $cat_ids)->update(['status'=> $change_status, 'reason'=> $request->reason]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
    }
    public function payout_details(Request $request)
    {
		$payout = Client_payout_request::where('id', $request->id)->first();
		
		$data['result'] = $payout;
		echo json_encode($data);
    }
}

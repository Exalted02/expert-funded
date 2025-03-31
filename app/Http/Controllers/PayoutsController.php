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
		
		$update = Client_payout_request::where('id', $request->id)->update(['status'=> $change_status]);
		
		$data['result'] = $change_status;
		echo json_encode($data);
    }
}

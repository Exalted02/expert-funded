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
}

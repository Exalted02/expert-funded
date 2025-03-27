<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kyc_documents;

class VerificationController extends Controller
{
    
    public function index()
    {
		$data = [];		
        return view('client.verification', $data);
    }
    public function save(Request $request)
    {
		$request->validate([
			'frontal_view_file' => 'required|mimes:jpg,png,pdf|max:5120',  // Max 5MB
			'back_view_file' => 'required|mimes:jpg,png,pdf|max:5120',
			'residence_file' => 'required|mimes:jpg,png,pdf|max:5120',
		], [
			'frontal_view_file.required' => 'Frontal view file is required.',
			'frontal_view_file.mimes' => 'Frontal view must be a file of type: jpg, png, pdf.',
			'frontal_view_file.max' => 'Frontal view file must not exceed 5MB.',
			
			'back_view_file.required' => 'Back view file is required.',
			'back_view_file.mimes' => 'Back view must be a file of type: jpg, png, pdf.',
			'back_view_file.max' => 'Back view file must not exceed 5MB.',

			'residence_file.required' => 'Proof of residence file is required.',
			'residence_file.mimes' => 'Proof of residence must be a file of type: jpg, png, pdf.',
			'residence_file.max' => 'Proof of residence file must not exceed 5MB.',
		]);
		
		if($request->hasFile('frontal_view_file')) {
			$destinationPath = public_path('uploads/kyc/'. auth()->user()->id .'/frontal');
			if (!file_exists($destinationPath)) {
				mkdir($destinationPath, 0777, true);
			}
			$file = $request->file('frontal_view_file');
			$frontalFile = time() . '_' . $file->getClientOriginalName();
			$file->move($destinationPath, $frontalFile);
		}
		
		if($request->hasFile('back_view_file')) {
			$destinationPath = public_path('uploads/kyc/'. auth()->user()->id .'/back');
			if (!file_exists($destinationPath)) {
				mkdir($destinationPath, 0777, true);
			}
			$file = $request->file('back_view_file');
			$backFile = time() . '_' . $file->getClientOriginalName();
			$file->move($destinationPath, $backFile);
		}
		
		if($request->hasFile('residence_file')) {
			$destinationPath = public_path('uploads/kyc/'. auth()->user()->id .'/residence');
			if (!file_exists($destinationPath)) {
				mkdir($destinationPath, 0777, true);
			}
			$file = $request->file('residence_file');
			$residencefile = time() . '_' . $file->getClientOriginalName();
			$file->move($destinationPath, $residencefile);
		}
		
		$model = new Kyc_documents();
		$model->client_id = auth()->user()->id;
		$model->frontal = $frontalFile;
		$model->back = $backFile;
		$model->residence = $residencefile;
		$model->status = 1;
		$model->created_at = date('Y-m-d h:i:s');
		$model->save();
		
		return back()->with('success', 'KYC documents uploaded successfully!');
    }
}

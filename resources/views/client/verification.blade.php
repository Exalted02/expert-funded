@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/c3-chart/c3.min.css') }}">
@endsection
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
            <div class="row">
                <div class="col-lg-12">
					<div class="card employee-month-card flex-fill">
						<div class="card-body">
							<div class="statistic-header">
								<h4>Verification Of Identity</h4>
							</div>
							<hr class="mt-0">
							<small>Your can submit your documents here</small>
							@if(session('success'))
								<div class="alert alert-success">
									{{ session('success') }}
								</div>
							@endif

							<form name="frm" action="{{ route('client.verification') }}" enctype="multipart/form-data" method="post">
							@csrf
							<div class="row mt-3 identity-verification">
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Frontal View (jpg, png or pdf)</small>
											<label class="dropzone">
												<div class="upload-content">
													<i class="la la-file-upload"></i>
													<span>UPLOAD HERE</span>
												</div>
												<input type="file" hidden name="frontal_view_file">
											</label>
										</div>
									</div>
									@if ($errors->has('frontal_view_file'))
										<span class="text-danger">{{ $errors->first('frontal_view_file') }}</span>
									@endif
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Back View (jpg, png or pdf)</small>
											<label class="dropzone">
												<div class="upload-content">
													<i class="la la-file-upload"></i>
													<span>UPLOAD HERE</span>
												</div>
												<input type="file" hidden name="back_view_file">
											</label>
										</div>
									</div>
									@if ($errors->has('back_view_file'))
										<span class="text-danger">{{ $errors->first('back_view_file') }}</span>
									@endif
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Proof Of Residence (jpg, png or pdf)</small>
											<label class="dropzone">
												<div class="upload-content">
													<i class="la la-file-upload"></i>
													<span>UPLOAD HERE</span>
												</div>
												<input type="file" hidden  name="residence_file">
											</label>
										</div>
									</div>
									@if ($errors->has('residence_file'))
										<span class="text-danger">{{ $errors->first('residence_file') }}</span>
									@endif
								</div>
							</div>
							<button class="btn btn-primary mt-3">
								<i class="la la-file-alt"></i> Submit KYC
							</button>
							</form>
						</div>
					</div>
				</div>
			</div>
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

@endsection 
@section('scripts')
<!-- Chart JS -->
<script src="{{ url('front-assets/plugins/c3-chart/d3.v5.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/c3.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/chart-data.js') }}"></script>

@endsection


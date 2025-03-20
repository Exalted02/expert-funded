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
								<h4>Account Settings</h4>
							</div>
							<hr class="mt-0">
							<div class="employee-month-details d-flex align-items-end justify-content-between mb-0">
								<div>
								<p>Your Email: beemnke@fgmfa.pl</p>
								<p>Name: Bilal</p>
								<p>Surname: Antekorowski</p>
								<p>Password:</p>
								</div>
								<div class="">
									<button class="btn btn-warning">
										<i class="las la-key"></i> Reset Your Password
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
                <div class="col-lg-12">
					<div class="card employee-month-card flex-fill">
						<div class="card-body">
							<div class="statistic-header">
								<h4>Payment Details</h4>
							</div>
							<hr class="mt-0">
							<div class="employee-month-details mb-0">
								<p>$100K Chllange | Made On 1 March | Paid: 500$</p>
								<p>$200K Chllange | Made On 3 March | Paid: 970$</p>
							</div>
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


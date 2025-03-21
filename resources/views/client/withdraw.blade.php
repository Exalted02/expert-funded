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
								<h4>Withdraw Simulated Funds</h4>
							</div>
							<hr class="mt-0">
							<small>Few Steps For Your Payout</small>
							<div class="row mt-3 identity-verification">
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-6">
									<div class="card employee-month-card flex-fill mb-0">
										<div class="card-body">
											<div class="statistic-header">
												<h4>Requirements For Withdrawl</h4>
											</div>
											<hr class="mt-0">
											<div class="stats-list">
												<div class="stats-info1">
													<p class="d-flex justify-content-between mb-0"><small>1 Trading Day</small> <small>5 Trading Days</small></p>
													<div class="progress">
														<div class="progress-bar bg-info" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
													</div>
												</div>
											</div>
											<div class="stats-list mt-4">
												<div class="stats-info1">
													<p class="d-flex justify-content-between mb-0"><small>10th Calender Day</small> <small>30 Calender Days</small></p>
													<div class="progress">
														<div class="progress-bar bg-info" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
													</div>
												</div>
											</div>
											<div class="d-flex justify-content-between mt-5">
												<div>
													<span class="d-block">You Will Be Eligible At:</span>
												</div>
												<div>
													<span class="">5 May, 2025</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-6">
									<div class="card employee-month-card flex-fill mb-0">
										<div class="card-body">
											<div class="statistic-header">
												<h4>Request Your Payout</h4>
											</div>
											<hr class="mt-0">
											<small>After the initial conditions, if profitable, you can request a payout of your shared profit</small>
											<div><button class="btn btn-primary w-100 mt-3">Submit A Withdrawl</button></div>
											<div class="d-flex justify-content-between mt-5">
												<div>
													<span class="d-block">Your Current Profit Split:</span>
												</div>
												<div>
													<span class="text-success">70%</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <div class="col-lg-12">
					<div class="card employee-month-card flex-fill">
						<div class="card-body">
							<div class="statistic-header">
								<h4>Latest Payouts</h4>
							</div>
							<hr class="mt-0">
							<div class="employee-month-details d-flex align-items-center justify-content-between mb-0">
								<p>2 March 2025 | Withdrawal Of $9,927 USD</p>
								<button class="btn btn-sm btn-warning">
									<i class="la la-hourglass"></i> Pending
								</button>
							</div>
							<div class="employee-month-details d-flex align-items-center justify-content-between mb-0 mt-1">
								<p>2 March 2025 | Withdrawal Of $9,927 USD</p>
								<button class="btn btn-sm btn-success">
									<i class="las la-check-double"></i> Confirmed
								</button>
							</div>
							<div class="employee-month-details d-flex align-items-center justify-content-between mb-0 mt-1">
								<p>2 March 2025 | Withdrawal Of $9,927 USD</p>
								<button class="btn btn-sm btn-danger">
									<i class="las la-times-circle"></i></i> Rejected
								</button>
							</div>
							<div class="employee-month-details d-flex align-items-center justify-content-between mb-0 mt-1">
								<p>2 March 2025 | Withdrawal Of $9,927 USD</p>
								<button class="btn btn-sm btn-danger">
									<i class="las la-times-circle"></i></i> Rejected
								</button>
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


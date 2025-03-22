@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/c3-chart/c3.min.css') }}">
@endsection
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
        
            <!-- Page Header -->
            {{--<div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome Admin!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>--}}
            <!-- /Page Header -->
        
            <div class="row">
                <div class="col-lg-4 col-md-12">
					<div class="card employee-welcome-card flex-fill">
						<div class="card-body client-dashboard-card-gradient">
							<div class="welcome-info">
								<div class="welcome-content">
									<h2>Current Balance</h2>
								</div>
								<div class="welcome-img">
									{{--<img src="assets/img/avatar/avatar-19.jpg" class="img-fluid" alt="User">--}}
									<i class="las la-wallet"></i>
								</div>
							</div>
							<hr>
							<div class="row align-items-center mt-3">
								<div class="col-6">
									<h6 class="mb-0">Equity</h6>
									<h3 class="fw-bold mt-1">$100,390</h3>
								</div>
								<div class="col-6 text-end">
									<span class="badge bg-inverse-white">+1%</span>
								</div>
							</div>
							<hr>
							<div class="row align-items-center mt-3">
								<div class="col-6">
									<h6 class="mb-0">Balance</h6>
									<h3 class="fw-bold mt-1">$100,390</h3>
								</div>
								<div class="col-6 text-end">
									<span class="badge bg-inverse-white">+1%</span>
								</div>
							</div>
							<hr>
							<div class="row align-items-center mt-3">
								<div class="col-6">
									<h6 class="mb-0">Eligible Withdrawl</h6>
									<h3 class="fw-bold mt-1">$390</h3>
								</div>
								<div class="col-6 text-end">
									<span class="badge bg-inverse-white">+1%</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Chart -->
				<div class="col-lg-8 col-md-12">	
					<div class="card">
						<div class="card-body">
							<div id="chart-sracked"></div>
						</div>
					</div>
				</div>
				<!-- /Chart -->
		   </div>
			<div class="row">
				<div class="col-md-12 col-lg-12 col-xl-4 d-flex">
					<div class="card flex-fill dash-statistics">
						<div class="card-body">
							<h5 class="card-title">Account Profit</h5>
							<div class="stats-list">
								<div class="stats-info">
									<p>$3,945 <strong>$8,000</strong></p>
									<div class="progress">
										<div class="progress-bar bg-info" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
									</div>
								</div>
							</div>
							<div class="d-flex justify-content-between mt-5">
								<div>
									<span class="d-block">Initial Balance:</span>
								</div>
								<div>
									<span class="">$100,000</span>
								</div>
							</div>
							<div class="d-flex justify-content-between">
								<div>
									<span class="d-block">Performance:</span>
								</div>
								<div>
									<span class="text-success">+15%</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-12 col-xl-4 d-flex">
					<div class="card flex-fill dash-statistics">
						<div class="card-body">
							<h5 class="card-title">Maximum Drawdown</h5>
							<div class="stats-list">
								<div class="stats-info">
									<p>-$3,945 <strong>-$8,000</strong></p>
									<div class="progress">
										<div class="progress-bar bg-danger" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: 55%"></div>
									</div>
								</div>
							</div>
							<div class="d-flex justify-content-between mt-5">
								<div>
									<span class="d-block">Initial Drawdown:</span>
								</div>
								<div>
									<span class="">$8,000</span>
								</div>
							</div>
							<div class="d-flex justify-content-between">
								<div>
									<span class="d-block">Left:</span>
								</div>
								<div>
									<span class="text-danger">$4,055</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-12 col-xl-4 d-flex">
					<div class="card flex-fill dash-statistics">
						<div class="card-body">
							<h5 class="card-title">Maximum Daily Drawdown</h5>
							<div class="stats-list">
								<div class="stats-info">
									<p>-$3,945 <strong>-$5,000</strong></p>
									<div class="progress">
										<div class="progress-bar bg-danger" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: 80%"></div>
									</div>
								</div>
							</div>
							<div class="d-flex justify-content-between mt-5">
								<div>
									<span class="d-block">Initial Daily Drawdown:</span>
								</div>
								<div>
									<span class="">$5,000</span>
								</div>
							</div>
							<div class="d-flex justify-content-between">
								<div>
									<span class="d-block">Left:</span>
								</div>
								<div>
									<span class="text-danger">$1,055</span>
								</div>
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
<script>
var chart = c3.generate({
	bindto: '#chart-sracked', // id of chart wrapper
	data: {
		columns: [
			// each columns data
			['data1', 0, 9, 16, 19, 30, 25 , 19, 12, 0],
		],
		type: 'area-spline', // default type of chart
		groups: [
			[ 'data1', 'data2']
		],
		colors: {
			data1:'#F175B1'
		},
		names: {
			// name of each serie
			'data1': 'Maximum'
		}
	},
	axis: {
		x: {
			type: 'category',
			// name of each category
			categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul' ,'Aug', 'Sep']
		},
	},
	legend: {
		  show: false, //hide legend
	},
	padding: {
		bottom: 0,
		top: 0
	},
});
setTimeout(() => {
    d3.selectAll(".c3-axis-x text").style("fill", "#FFFFFF"); // X-axis text color
    d3.selectAll(".c3-axis-y text").style("fill", "#FFFFFF"); // Y-axis text color
}, 500);
</script>

@endsection


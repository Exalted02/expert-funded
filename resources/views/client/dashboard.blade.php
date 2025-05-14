@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/c3-chart/c3.min.css') }}">
<style>
.tooltip-custom {
    background: rgba(0, 0, 0, 0.85);
    padding: 10px;
    border-radius: 8px;
    color: #fff;
    font-family: sans-serif;
}

</style>
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
									<h3 class="fw-bold mt-1">{{get_currency_symbol()}}{{$equity_amount}}</h3>
								</div>
								<div class="col-6 text-end">
									<span class="badge bg-inverse-white">{{ $equity_percent < 0 ? $equity_percent : '+' . $equity_percent }}%</span>
								</div>
							</div>
							<hr>
							<div class="row align-items-center mt-3">
								<div class="col-6">
									<h6 class="mb-0">Balance</h6>
									<h3 class="fw-bold mt-1">{{get_currency_symbol()}}{{$total_balance}}</h3>
								</div>
								<div class="col-6 text-end">
									<span class="badge bg-inverse-white">{{ $equity_percent < 0 ? $equity_percent : '+' . $equity_percent }}%</span>
								</div>
							</div>
							{{--<hr>
							<div class="row align-items-center mt-3">
								<div class="col-6">
									<h6 class="mb-0">Eligible Withdrawl</h6>
									<h3 class="fw-bold mt-1">{{get_currency_symbol()}}{{$eligible_withdraw}}</h3>
								</div>
								<div class="col-6 text-end">
									<span class="badge bg-inverse-white">+1%</span>
								</div>
							</div>--}}
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
			{{--<div class="row">
				<div class="col-md-12 col-lg-12 col-xl-12 d-flex">
					<div class="card flex-fill dash-statistics">
						<div class="card-body">
							<div class="row">
								<div class="col-md-3">
									<div class="d-flex">
										<div>
											<span class="d-block">Date: {{change_date_format($challenge_val->trade_date, 'Y-m-d', 'd-m-Y')}}</span>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="d-flex">
										<div>
											<span class="d-block">Trades: {{$challenge_val->trade_count}}</span>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="d-flex">
										<div>
											<span class="d-block">Most Traded Pair: {{$challenge_val->trade_pair}}</span>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="d-flex">
										<div>
											<span class="d-block">Result: {{ $challenge_val->trade_result < 0 ? $challenge_val->trade_result : '+' . $challenge_val->trade_result }} %</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>--}}
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped custom-table datatable3">
							<thead>
								<tr>
									<th>Date</th>
									<th>Trades</th>
									<th>Most Traded Pair</th>
									<th>Result (%)</th>
								</tr>
							</thead>
							<tbody>
								@foreach($adj_rec as $adj_rec_val)
								<tr>
									<td>{{ change_date_format($adj_rec_val['date'], 'Y-m-d', 'd M y') }}</td>
									<td>{{ $adj_rec_val['trades'] ?? 0 }}</td>
									<td>{{ $adj_rec_val['trade_pair'] ?? '' }}</td>
									<td>{{ $adj_rec_val['trade_result'] ?? '0' }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-lg-12 col-xl-4 d-flex">
					<div class="card flex-fill dash-statistics">
						<div class="card-body">
							<h5 class="card-title">Account Profit</h5>
							<div class="stats-list">
								<div class="stats-info">
									@php
										$last_account_profit = $initial_amount * (10/100);
										$percentage_account_profit = ($last_account_profit > 0) ? ($amount_paid_balance / $last_account_profit) * 100 : 0;
									@endphp
									<p>{{get_currency_symbol()}}{{$amount_paid_balance}} <strong>{{get_currency_symbol()}}{{$last_account_profit}}</strong></p>
									<div class="progress">
										<div class="progress-bar {{ $percentage_account_profit < 0 ? 'bg-danger' : 'bg-info' }}" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: {{abs($percentage_account_profit)}}%"></div>
									</div>
								</div>
							</div>
							<div class="d-flex justify-content-between mt-5">
								<div>
									<span class="d-block">Initial Balance:</span>
								</div>
								<div>
									<span class="">{{get_currency_symbol()}}{{$initial_amount}}</span>
								</div>
							</div>
							<div class="d-flex justify-content-between">
								<div>
									<span class="d-block">Performance:</span>
								</div>
								<div>
									<span class="text-success {{ $equity_percent < 0 ? 'text-danger' : '' }}">{{ $equity_percent < 0 ? $equity_percent : '+' . $equity_percent }}%</span>
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
									@php
										$last_maximum_drawdown = $initial_amount * (10/100);
										$percentage_maximum_drawdown = ($last_maximum_drawdown > 0) ? ($amount_paid_balance / $last_maximum_drawdown) * 100 : 0;
									@endphp
									<p>{{get_currency_symbol()}}{{$amount_paid_balance}} <strong>{{get_currency_symbol()}}{{$last_maximum_drawdown}}</strong></p>
									<div class="progress">
										<div class="progress-bar {{ $percentage_maximum_drawdown < 0 ? 'bg-danger' : 'bg-info' }}" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: {{abs($percentage_maximum_drawdown)}}%"></div>
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
							{{--<div class="d-flex justify-content-between">
								<div>
									<span class="d-block">Left:</span>
								</div>
								<div>
									<span class="text-danger">$4,055</span>
								</div>
							</div>--}}
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-12 col-xl-4 d-flex">
					<div class="card flex-fill dash-statistics">
						<div class="card-body">
							<h5 class="card-title">Maximum Daily Drawdown</h5>
							<div class="stats-list">
								<div class="stats-info">
									@php
										$last_maximum_daily_drawdown = $total_balance * (5/100);
										$percentage_maximum_daily_drawdown = ($last_maximum_daily_drawdown > 0) ? ($amount_paid_balance / $last_maximum_daily_drawdown) * 100 : 0;
									@endphp
									<p>{{get_currency_symbol()}}{{$amount_paid_balance}} <strong>{{get_currency_symbol()}}{{$last_maximum_daily_drawdown}}</strong></p>
									<div class="progress">
										<div class="progress-bar {{ $percentage_maximum_daily_drawdown < 0 ? 'bg-danger' : 'bg-info' }}" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: {{abs($percentage_maximum_daily_drawdown)}}%"></div>
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
							{{--<div class="d-flex justify-content-between">
								<div>
									<span class="d-block">Left:</span>
								</div>
								<div>
									<span class="text-danger">$1,055</span>
								</div>
							</div>--}}
						</div>
					</div>
				</div>
			</div>
        
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->
@php
    $min = min($chartData);
    $max = max($chartData);
    $range = $max - $min;
    $buffer = $range * 0.3; // 30% buffer
    $yMin = floor($min - $buffer);
    $yMax = ceil($max + $buffer);
@endphp
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
			['data1', ...@json($chartData)],
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
			categories: @json($chartLabels)
		},
		y: {
			min: {{ $yMin }},
			max: {{ $yMax }},
			padding: {
				top: 0,
				bottom: 0
			},
			tick: {
				format: d3.format(",") // optional, formats large numbers with commas
			}
		}
	},
	legend: {
		  show: false, //hide legend
	},
	padding: {
		bottom: 0,
		top: 0
	},
	tooltip: {
		contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
			var data = d[0];
			var index = data.index;

			// Get tooltip data from controller
			var tooltipData = @json($tooltipData);

			// Fallback in case of missing data
			if (!tooltipData[index]) return '';

			var item = tooltipData[index];

			return `
				<div class="tooltip-custom text-white text-sm">
					<strong>${@json($chartLabels)[index]}</strong><br/>
					Balance: <strong>$${item.balance.toLocaleString()}</strong><br/>
					Target: <strong>$${(item.target ?? 0).toLocaleString()}</strong><br/>
					Max Drawdown: <strong>$${item.max_drawdown.toLocaleString()}</strong><br/>
					Max Daily Loss: <strong>$${item.max_daily_loss.toLocaleString()}</strong><br/>
					Equity: <strong>$${item.equity.toLocaleString()}</strong>
				</div>
			`;
		}
	}
});
setTimeout(() => {
    d3.selectAll(".c3-axis-x text").style("fill", "#FFFFFF"); // X-axis text color
    d3.selectAll(".c3-axis-y text").style("fill", "#FFFFFF"); // Y-axis text color
}, 500);
</script>

@endsection


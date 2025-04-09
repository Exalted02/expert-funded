@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/c3-chart/c3.min.css') }}">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
@endsection
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
			<div class="multiple-items">
				@foreach($challenge as $k=>$val)
				<div class="card employee-month-card flex-fill" style="margin-right: 15px;">
					<div class="card-body">
						<div class="row">
							<div class="col-md-5">
								@if($val->status == 0)
								<button class="btn btn-square btn-outline-warning"><i class="las la-clock"></i> On Challenge</button>
								@elseif($val->status == 1)
								<button class="btn btn-square btn-outline-success"><i class="las la-check-double"></i> Funded</button>
								@elseif($val->status == 2)
								<button class="btn btn-square btn-outline-danger"><i class="las la-times-circle"></i> Failed</button>
								@endif
								
								<div class="mt-3">
									<p>#{{$k+1}} - {{$val->get_challenge_type->title}}</p>
								</div>
								<div class="mt-3">
									<h3>Equity</h3>
									<h4 class="mt-1"><strong>{{get_currency_symbol()}}{{$val->amount_paid + $val->get_challenge_type->amount}}</strong></h4>
								</div>
								<div class="mt-3">
									<h2>Balance</h2>
									<h3 class="mt-1"><strong>{{get_currency_symbol()}}{{$val->amount_paid + $val->get_challenge_type->amount}}</strong></h3>
								</div>
								<div class="mt-3">
									<a href="{{ route('client.dashboard', [$val->id]) }}"><button class="btn btn-primary"><i class="las la-eye"></i> View Dashboard</button></a>
								</div>
							</div>
							<div class="col-md-7">
								<div id="chart-sracked-{{$k}}"></div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

@endsection 
@section('scripts')
<!-- Chart JS -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="{{ url('front-assets/plugins/c3-chart/d3.v5.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/c3.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/chart-data.js') }}"></script>
<script>
$('.multiple-items').slick({
  infinite: false,
  slidesToShow: 2,
  slidesToScroll: 2,
  nextArrow: '<i class="las la-chevron-circle-right"></i>',
  prevArrow: '<i class="las la-chevron-circle-left"></i>',
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        // centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1,
		slidesToScroll: 1,
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        // centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1,
		slidesToScroll: 1,
      }
    }
  ]
});

// Sample dummy data array, ideally you'll pass this from the controller or use dynamic server data
var challenges = @json($challenge);

challenges.forEach((item, index) => {
	let chartId = `#chart-sracked-${index}`;
	c3.generate({
		bindto: chartId,
		data: {
			columns: [
				['data1', 0, 9, 16, 19, 30, 25 , 19, 12, 0],
			],
			type: 'area-spline',
			groups: [['data1']],
			colors: {
				data1:'#F175B1'
			},
			names: {
				'data1': 'Maximum'
			}
		},
		axis: {
			x: {
				type: 'category',
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul' ,'Aug', 'Sep']
			},
		},
		legend: {
			show: false
		},
		padding: {
			bottom: 0,
			top: 0
		},
	});
});
setTimeout(() => {
    d3.selectAll(".c3-axis-x text").style("fill", "#FFFFFF"); // X-axis text color
    d3.selectAll(".c3-axis-y text").style("fill", "#FFFFFF"); // Y-axis text color
}, 500);
</script>

@endsection


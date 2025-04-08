@extends('layouts.app')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col-md-4">
					<h3 class="page-title">Challenges</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="">Challenges</a></li>
						<li class="breadcrumb-item active">Challenges</li>
					</ul>
				</div>
				<div class="col-md-8 float-end ms-auto">
					<div class="d-flex title-head">
						{{--<div class="form-sort m-r-5">
							<a href="javascript:void(0);" class="list-view btn btn-link"><i class="fa-solid fa-file-export"></i>Download CSV</a>
						</div>--}}
						<a href="javascript:void(0)" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_challenge"><i class="la la-plus-circle"></i> Create Challenges</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		{{--<div class="filter-filelds">
			<div class="row filter-row">
				<div class="col-xl-2">  
					 <div class="input-block">
						 <select class="select" name="search_status">
							<option value="">{{ __('please_select') }}</option>
							<option value="1">Last 30 Days</option>
							<option value="0">Last 2 Months</option>
						</select>
					 </div>
				</div>
				<div class="col-xl-2">  
					 <div class="input-block">
						 <select class="select" name="search_status">
							<option value="1">All Steps</option>
						</select>
					 </div>
				</div>
				<div class="col-xl-2">  
					 <div class="input-block">
						 <select class="select" name="search_status">
							<option value="1">All Challenges</option>
						</select>
					 </div>
				</div>
				<div class="col-xl-2">  
					 <div class="input-block">
						 <select class="select" name="search_status">
							<option value="1">All States</option>
						</select>
					 </div>
				</div>
				<div class="col-xl-2">  
					 <div class="input-block">
						 <input type="search" class="form-control floating" name="search_name" placeholder="Search by email">
					 </div>
				</div>
				<div class="col-xl-2 p-r-0">
					<button type="reset" class="btn custom-reset w-100 reset-button" data-id="1">
						<i class="fa-solid fa-rotate-left"></i> Reset
					</button>
				</div>
			</div>
		</div>--}}
		
		<hr>
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable">
						<thead>
							<tr>
								{{--<th>Trader Account</th>--}}
								<th>Trader Email</th>
								<th>Trader Name</th>
								<th>Challenge</th>
								<th>Balance</th>
								<th>State</th>
								{{--<th>Tag</th>
								<th>Step</th>
								<th>Equity</th>
								<th>Broker Group</th>
								<th>Proof Document</th>--}}
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($list as $val)
							<tr>
								{{--<td>9002207</td>--}}
								<td>{{$val->email}}</td>
								<td>{{$val->first_name.' '.$val->last_name}}</td>
								<td>{{$val->get_challenge_type->title}}</td>
								<td>{{$val->amount_paid}}</td>
								<td>
									{{--<button type="button" class="btn btn-sm btn-outline-danger rounded-pill">Failed</button>--}}
									<div class="dropdown action-label">
										@if($val->status == 0)
										<a class="btn btn-white btn-sm badge-outline-primary dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-primary"></i> On Challenge
										</a>
										@elseif($val->status == 1)
										<a class="btn btn-white btn-sm badge-outline-success dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-success"></i> Funded
										</a>
										@elseif($val->status == 2)
										<a class="btn btn-white btn-sm badge-outline-danger dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-danger"></i> Failed
										</a>
										@endif
										
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{route('challenges.challenge-update-status')}}" data-type="0"><i class="fa-regular fa-circle-dot text-primary"></i> On Challenge</a>
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{route('challenges.challenge-update-status')}}" data-type="1"><i class="fa-regular fa-circle-dot text-success"></i> Funded</a>
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{route('challenges.challenge-update-status')}}" data-type="2"><i class="fa-regular fa-circle-dot text-danger"></i> Failed</a>
										</div>
									</div>
								</td>
								{{--<td>None</td>
								<td>Phase 1</td>
								<td>$15,256,12</td>
								<td>ST_USD(DEMO)</td>
								<td> </td>--}}
								<td class="text-end">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item challenge-details" href="javascript:void(0)" data-id="{{ $val->id}}" data-url="{{ route('challenges.challenge-details') }}"><i class="fa-regular fa-eye m-r-5"></i> See Details</a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach	
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- /Page Content -->
@include('modal.challenges-modal')
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script src="{{ url('front-assets/js/page/challenges.js') }}"></script>
@endsection

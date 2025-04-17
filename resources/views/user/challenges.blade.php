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
						<a href="javascript:void(0)" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#import_challenge"><i class="la la-plus-circle"></i> Import Challenges</a>
						<a href="javascript:void(0)" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_challenge"><i class="la la-plus-circle"></i> Create Challenges</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<div class="filter-filelds">
			<form name="search-frm" method="post" action="{{ route('challenges.index') }}" id="search-challenge">
			@csrf
				<div class="row filter-row">
					<div class="col-md-3">  
						 <div class="input-block">
							<select class="select" name="search_status">
								<option value="">{{ __('please_select') }}</option>
								<option value="0" {{ $search_status == 0 ? 'selected' : '' }}>On Challenge</option>
								<option value="1" {{ $search_status == 1 ? 'selected' : '' }}>Funded</option>
								<option value="2" {{ $search_status == 2 ? 'selected' : '' }}>Failed</option>
							</select>
						 </div>
					</div>
					<div class="col-xl-2 p-r-0">  
						<a href="javascript:void(0);" class="btn btn-success w-100 search-data"><i class="fa-solid fa-magnifying-glass"></i> {{ __('search') }} </a> 
					</div>
				</div>
			</form>
		</div>
		
		<div class="row">
			<div class="col-md-3 mb-2">
				<button type="button" class="btn btn-info multi-adjust-balance"><i class="la la-plus m-r-5"></i> Adjust balance</button>
			</div>
		</div>
		<hr>
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable">
						<thead>
							<tr>
								@if($list->count() > 0)
								<th>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="checkAll">
									</label>
								</th>
								@endif
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
								@if($list->count() > 0)
								<td>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="chk_id" data-emp-id="{{ $val->id }}">
									</label>
								</td>
								@endif
								{{--<td>9002207</td>--}}
								<td>{{$val->email}}</td>
								<td>{{$val->first_name.' '.$val->last_name}}</td>
								<td>{{$val->get_challenge_type->title}}</td>
								@php
									$amount_paid = App\Models\Adjust_users_balance::where('challenge_id', $val->id)->where('type', 1)->sum('amount_paid');
								@endphp
								<td>{{$amount_paid + $val->get_challenge_type->amount}}</td>
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
											<a class="dropdown-item adjust-balance" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('challenges.challenge-ajax-details') }}"><i class="la la-plus m-r-5"></i> Adjust balance</a>
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
<script>
$(document).on('click','.search-data', function(){
	$('#search-challenge').submit();
});
</script>
@endsection

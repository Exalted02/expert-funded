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
					<h3 class="page-title">User Accounts</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="">User</a></li>
						<li class="breadcrumb-item active">User</li>
					</ul>
				</div>
				{{--<div class="col-md-8 float-end ms-auto">
					<div class="d-flex title-head">
						<div class="view-icons">
							<a href="javascript:void(0);" class="list-view btn btn-link" id="filter_search"><i class="las la-filter"></i></a>
						</div>
					</div>
				</div>--}}
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		{{--<div class="filter-filelds" id="filter_inputs">
			<form name="search-frm" method="post" action="" id="search-product-code-frm">
				@csrf
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
							 <input type="search" class="form-control floating" name="search_name" placeholder="Search by email">
						 </div>
					</div>
					<div class="col-xl-2">  
					<a href="javascript:void(0);" class="btn btn-success w-100 search-data"><i class="fa-solid fa-magnifying-glass"></i> {{ __('search') }} </a> 
					</div>
					<div class="col-xl-2 p-r-0">
						<button type="reset" class="btn custom-reset w-100 reset-button" data-id="1">
							<i class="fa-solid fa-rotate-left"></i> Reset
						</button>
					</div>
				</div>
			</form>
		</div>--}}
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
						 <input type="search" class="form-control floating" name="search_name" placeholder="Search by email">
					 </div>
				</div>
				<div class="col-xl-2">  
				<a href="javascript:void(0);" class="btn btn-success w-100 search-data"><i class="fa-solid fa-magnifying-glass"></i> {{ __('search') }} </a> 
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
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								{{--<th>Country</th>
								<th>Address</th>--}}
								<th>Created At</th>
								<th>Status</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($list as $val)
							<tr>
								<td>{{$val->name ?? ''}}</td>
								<td>{{$val->email ?? ''}}</td>
								<td>{{$val->phone_number ?? ''}}</td>
								{{--<td>-</td>
								<td>-</td>--}}
								<td>{{change_date_format($val->created_at, 'Y-m-d H:i:s', 'd M y')}} </td>
								<td>
								@if($val->status ==1)
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm badge-outline-success dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-success"></i> {{ __('active') }}
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('user-update-status') }}"><i class="fa-regular fa-circle-dot text-success"></i> {{ __('active') }}</a>
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('user-update-status') }}"><i class="fa-regular fa-circle-dot text-danger"></i> Suspended</a>
										</div>
									</div>
								 @else
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm badge-outline-danger dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-danger"></i> Suspended
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('user-update-status') }}"><i class="fa-regular fa-circle-dot text-success"></i> {{ __('active') }}</a>
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $val->id }}" data-url="{{ route('user-update-status') }}"><i class="fa-regular fa-circle-dot text-danger"></i> Suspended</a>
										</div>
									</div> 
								 
								 @endif
								</td>
								<td class="text-end">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item" href="javascript:void(0);" data-id="" data-url=""><i class="fa-solid fa-pencil m-r-5"></i> {{ __('edit') }}</a>
											<a class="dropdown-item" href=""><i class="fa-regular fa-eye m-r-5"></i> {{ __('view') }}</a>
											<a class="dropdown-item" href="javascript:void(0);" data-id="" data-url=""><i class="fa-regular fa-trash-can m-r-5"></i> {{ __('delete') }}</a>
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
@include('modal.user-modal')
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script src="{{ url('front-assets/js/page/user.js') }}"></script>
@endsection

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
					<h3 class="page-title">Payouts</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="">Payouts</a></li>
						<li class="breadcrumb-item active">Payouts</li>
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
		<div class="filter-filelds">
			<div class="row filter-row">
				<div class="col-xl-2">  
					 <div class="input-block">
						 <select class="select" name="search_status">
							<option value="">All History</option>
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
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
				<div class="card dash-widget">
					<div class="card-body">
						<span class="dash-widget-icon"><i class="fa-solid fa-money-bill-wave text-primary"></i></span>
						<div class="dash-widget-info">
							<h3 class="text-primary">112</h3>
							<span>Pending Payouts</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
				<div class="card dash-widget">
					<div class="card-body">
						<span class="dash-widget-icon"><i class="fa-solid fa-xmark text-danger"></i></span>
						<div class="dash-widget-info">
							<h3 class="text-danger">44</h3>
							<span>Rejected Payouts</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
				<div class="card dash-widget">
					<div class="card-body">
						<span class="dash-widget-icon"><i class="fa-solid fa-check text-success"></i></span>
						<div class="dash-widget-info">
							<h3 class="text-success">37</h3>
							<span>Completed Payouts</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable">
						<thead>
							<tr>
								<th>Trader Account</th>
								<th>Trader Email</th>
								<th>Created At</th>
								<th>Amount</th>
								<th>Challenge</th>
								<th>Status</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>9011204</td>
								<td>sarango@gmail.com</td>
								<td>18 Mar 25</td>
								<td>$1,730,55</td>
								<td>100K 1 Phase(FUNDED</td>
								{{--<td><span class="text-danger">Cancelled</span></td>--}}
								<td>
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm badge-outline-success dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-success"></i> {{ __('pending') }}
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="" data-url=""><i class="fa-regular fa-circle-dot text-success"></i> {{ __('pending') }}</a>
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="" data-url=""><i class="fa-regular fa-circle-dot text-danger"></i> {{ __('cancelled') }}</a>
										</div>
									</div>
								</td>
								<td class="text-end">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item" href=""><i class="fa-regular fa-eye m-r-5"></i> See Details</a>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>9010485</td>
								<td>decyanjohnson@gmail.com</td>
								<td>17 Mar 25</td>
								<td>$7,072,80</td>
								<td>100K 1 Phase(FUNDED)</td>
								<td>
									<div class="dropdown action-label">
										<a class="btn btn-white btn-sm badge-outline-danger dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="fa-regular fa-circle-dot text-danger"></i> {{ __('cancelled') }}
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="" data-url=""><i class="fa-regular fa-circle-dot text-success"></i> {{ __('pending') }}</a>
											<a class="dropdown-item update-status" href="javascript:void(0);" data-id="" data-url=""><i class="fa-regular fa-circle-dot text-danger"></i> {{ __('cancelled') }}</a>
										</div>
									</div>
								</td>
								<td class="text-end">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item" href=""><i class="fa-regular fa-eye m-r-5"></i> See Details</a>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- /Page Content -->
@include('modal.payouts-modal')
@include('modal.common')
@endsection 
@section('scripts')
@include('_includes.footer')
<script src="{{ url('front-assets/js/page/payouts.js') }}"></script>
@endsection

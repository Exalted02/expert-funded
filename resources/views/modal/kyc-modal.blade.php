<div class="modal custom-modal fade" id="delete_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon bg-danger" id="delete-prospect-msg">
						<i class="la la-trash-restore"></i>
					</div>
					<h3>{{ __('are_you_sure') }}, {{ __('you_want_delete') }}</h3>
					<p>{{ __('customer') }} "<span id="list_name"></span>" {{ __('from_your_account') }}</p>
					<div class="col-lg-12 text-center form-wizard-button">
						<a href="#" class="button btn-lights" data-bs-dismiss="modal">{{ __('not_now') }}</a>
						<a href="javascript:void(0);" class="btn btn-primary data-id-pcode-list" data-url="">{{ __('okay') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- View Details -->
<div class="modal custom-modal fade" id="view_details" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">KYC Submission(id 26508)
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<ul class="personal-info">
					<li>
						<div class="title">Email:</div>
						<div class="text"></div>
					</li>
					<li>
						<div class="title">Trader ID:</div>
						<div class="text"></div>
					</li>
					<li>
						<div class="title">Full Name:</div>
						<div class="text"></div>
					</li>
					<li>
						<div class="title">Status:</div>
						<div class="text text-warning">Pending</div>
					</li>
					<li>
						<div class="title">Created At:</div>
						<div class="text"></div>
					</li>
					<li>
						<div class="title">Downloadable Documents:</div>
						<div class="text"></div>
					</li>
					<li class="dash-statistics">
						<div class="row stats-info">
							<div class="col-4">
								<div class="file-download text-center">
									<a href="javascript:void(0)" class="color-white">Frontal ID</a>
									<a class="btn btn-sm w-100 btn-info rounded-pill" href="#"><i class="la la-eye"></i> View</a>
								</div>
							</div>
							<div class="col-4">
								<div class="file-download text-center">
									<a href="javascript:void(0)" class="color-white">Back ID</a>
									<a class="btn btn-sm w-100 btn-info rounded-pill" href="#"><i class="la la-eye"></i> View</a>
								</div>
							</div>
							<div class="col-4">
								<div class="file-download text-center">
									<a href="javascript:void(0)" class="color-white">Residence ID</a>
									<a class="btn btn-sm w-100 btn-info rounded-pill" href="#"><i class="la la-eye"></i> View</a>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-danger"><i class="las la-times-circle"></i> Reject</a>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-success"><i class="las la-check-double"></i> Accept</a>
						</div>
						<div class="col-12 mt-3">
							<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-secondary">Close</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- update Success message -->
<div class="modal custom-modal fade" id="updt_success_msg" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon">
						<i class="la la-pencil"></i>
					</div>
					<h3>{{ __('data_updated_successfully') }}!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>

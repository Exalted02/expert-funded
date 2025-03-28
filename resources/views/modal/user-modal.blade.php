<div class="modal custom-modal fade" id="delete_model" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon bg-danger" id="delete-icon">
						<i class="la la-trash-restore"></i>
					</div>
					<h3>Are you sure, you want to delete</h3>
					<p>uaer "<span id="delete_modal_name_data"></span>" from your account?</p>
					<div class="col-lg-12 text-center form-wizard-button">
						<a href="#" class="button btn-lights" data-bs-dismiss="modal">{{ __('not_now') }}</a>
						<a href="javascript:void(0);" class="btn btn-primary final-delete-submit" data-url="{{ route('users.final_delete_submit') }}">{{ __('okay') }}</a>
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
					<h3>Data updated successfully!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- update Success message -->
<div class="modal custom-modal fade" id="update_status" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon">
						<i class="la la-pencil"></i>
					</div>
					<h3>Account status updated successfully!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Edit user data -->
<div id="edit_user" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit User</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frmUserSubmit" action="{{ route('users.user-data-submit') }}">
					<input type="hidden" name="id" id="id">
					<div class="row">
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Email<span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="email" id="email" placeholder="Email">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">First Name<span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="first_name" id="first_name" placeholder="First Name">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Last Name<span class="text-danger">*</span></label>
								<input class="form-control" type="text" name="last_name" id="last_name" placeholder="Last Name">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="input-block mb-3">
								<label class="col-form-label">Phone (Optional)</label>
								<input class="form-control" type="text" name="phone_number" id="phone_number" placeholder="Phone (Optional)">
								<div class="invalid-feedback"></div>
							</div>
						</div>
					</div>					
					<div class="modal-btn delete-action mt-3">
						<div class="row">
							<div class="col-6">
								<a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-secondary">Cancel</a>
							</div>
							<div class="col-6">
								<a href="javascript:void(0);" class="btn btn-sm w-100 btn-primary update-user">Update</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Edit user data -->

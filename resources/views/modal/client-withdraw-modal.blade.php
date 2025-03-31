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

<!-- Adjust Balance Model -->
<div class="modal custom-modal fade" id="submit_withdraw_request" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLgLabel">Submit Withdraw
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<ul class="personal-info">
					<li>
						<div class="title">Existing withdrawable Balance({{get_currency_symbol()}}) :</div>
						<div class="text"><span id="withdrawable_balance_text"></span></div>
					</li>
					<li class="dash-statistics">
						<div class="row w-100">
							<div class="col-12">
								<form id="frmWithdrawSubmit" action="{{ route('client.withdraw.withdraw-submit') }}">
									<input type="hidden" name="withdrawable_balance_input" id="withdrawable_balance_input">
									<input type="hidden" name="withdrawable_id" id="withdrawable_id">
								</form>
							</div>
						</div>
					</li>
				</ul>
				<div class="modal-btn delete-action" id="withdraw-submit-section">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" class="btn btn-sm w-100 btn-danger" data-bs-dismiss="modal"><i class="las la-times-circle"></i> cancel</a>
						</div>
						<div class="col-6">
							<button href="javascript:void(0);" class="btn btn-sm w-100 btn-success submit-withdraw-request"><i class="las la-check-double"></i> Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- update Success message -->
<div class="modal custom-modal fade" id="request_withdraw_msg_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon">
						<i class="la la-money-bill-alt"></i>
					</div>
					<h3 id="request_withdraw_msg"></h3>
				</div>
			</div>
		</div>
	</div>
</div>

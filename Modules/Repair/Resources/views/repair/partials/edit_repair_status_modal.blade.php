<div class="modal-dialog" role="document">
	<div class="modal-content">

		{{-- <form action="{{ action('\\Modules\\Repair\\Http\\Controllers\\RepairController@updateRepairStatus') }}" method="post" id="update_repair_status_form"> --}}
			<form action="{{ route('repair-updateRepairStatus') }}" method="post" id="update_repair_status_form">

			@csrf
			<input type="hidden" name="repair_id" id="repair_id" value="{{ $transaction->id }}">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">@lang( 'repair::lang.edit_status' )</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<strong>@lang('sale.invoice_no'): </strong>
						<span id="repair_no">{{$transaction->invoice_no}}</span>
					</div>
				</div>
				<div class="row mt-15">
					<div class="col-md-12">
						<div class="form-group">
							<label for="repair_status_id_modal">{{ __('sale.status') . ':*' }}</label>
							<select name="repair_status_id_modal" id="repair_status_id_modal" class="form-control select2" required style="width:100%">
								<option value="">{{ __('messages.please_select') }}</option>
								@foreach($repair_status_dropdown['statuses'] as $key => $value)
									<option value="{{ $key }}" {{ $transaction->repair_status_id == $key ? 'selected' : '' }}>{{ $value }}</option>
								@endforeach
							</select>
							{{-- If you need to render the template, you can do so below or with JS: $repair_status_dropdown['template'] --}}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="update_note">{{ __( 'repair::lang.update_note' ) . ':' }}</label>
							<textarea name="update_note" id="update_note" class="form-control" placeholder="{{ __( 'repair::lang.update_note' ) }}" rows="3"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="send_sms" value="1" id="send_sms"> @lang('repair::lang.send_sms')
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-12 sms_body" style="display: none !important;">
						<div class="form-group">
							<label for="sms_body">{{ __( 'lang_v1.sms_body' ) . ':' }}</label>
							<textarea name="sms_body" id="sms_body" class="form-control" placeholder="{{ __( 'lang_v1.sms_body' ) }}" rows="4"></textarea>
							<p class="help-block">
								<label>{{$status_template_tags['help_text']}}:</label><br>
								{{implode(', ', $status_template_tags['tags'])}}
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="submit" class="btn btn-primary ladda-button">@lang( 'messages.update' )</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
			</div>

		</form>

	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
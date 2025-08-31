<div class="modal-dialog" role="document">
	<div class="modal-content">
		<form action="{{ action('\\Modules\\Repair\\Http\\Controllers\\JobSheetController@updateStatus', [$job_sheet->id]) }}" method="POST" id="update_status_form">
			@csrf
			@method('PUT')
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">@lang( 'repair::lang.edit_status' )</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<strong>
							@lang('repair::lang.job_sheet_no'):
						</strong>
						<span id="job_sheet_no">
							{{$job_sheet->job_sheet_no}}
						</span>
					</div>
				</div>
				<div class="row mt-15">
					<div class="col-md-12">
						<div class="form-group">
							<label for="status_id_modal">{{ __('sale.status') }}:*</label>
							<select name="status_id" id="status_id_modal" class="form-control select2" required style="width:100%">
								<option value="">{{ __('messages.please_select') }}</option>
								@foreach($status_dropdown['statuses'] as $id => $status)
									<option value="{{ $id }}" @if($job_sheet->status_id == $id) selected @endif>{{ $status }}</option>
								@endforeach
							</select>
							{{-- $status_dropdown['template'] is not used in standard HTML select --}}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="update_note">{{ __( 'repair::lang.update_note' ) }}:</label>
							<textarea name="update_note" class="form-control" placeholder="{{ __( 'repair::lang.update_note' ) }}" rows="3" id="update_note"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<div class="checkbox-inline">
								<label>
									<input type="checkbox" name="send_sms" value="1" id="send_sms"> @lang('repair::lang.send_sms')
								</label>
							</div>
							<div class="checkbox-inline">
								<label>
									<input type="checkbox" name="send_email" value="1" id="send_email"> @lang('repair::lang.send_email')
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-12 sms_body" style="display: none !important;">
						<div class="form-group">
							<label for="sms_body">{{ __( 'lang_v1.sms_body' ) }}:</label>
							<textarea name="sms_body" class="form-control" placeholder="{{ __( 'lang_v1.sms_body' ) }}" rows="4" id="sms_body"></textarea>
							<p class="help-block">
								<label>{{$status_template_tags['help_text']}}:</label><br>
								{{implode(', ', $status_template_tags['tags'])}}
							</p>
						</div>
					</div>
				</div>
				<div class="row email_template" style="display: none !important;">
					<div class="col-md-12">
						<div class="form-group">
							<label for="email_subject">{{ __( 'lang_v1.email_subject' ) }}:</label>
							<input type="text" name="email_subject" class="form-control" placeholder="{{ __( 'lang_v1.email_subject' ) }}" id="email_subject">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="email_body">{{ __( 'lang_v1.email_body' ) }}:</label>
							<textarea name="email_body" class="form-control" placeholder="{{ __( 'lang_v1.email_body' ) }}" rows="5" id="email_body"></textarea>
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
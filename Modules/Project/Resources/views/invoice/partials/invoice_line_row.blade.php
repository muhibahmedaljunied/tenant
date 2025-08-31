<div class="invoice_line_row hide">
	<div class="col-md-12 il-bg invoice_line">
		<div class="mt-10">
			<div class="col-md-3">
				<div class="form-group">
					<div class="input-group">
						<input type="text" name="task[]" class="form-control" required>
						<span class="input-group-btn">
					        <button class="btn btn-default toggle_description" type="button">
								<i class="fa fa-info-circle text-info" data-toggle="tooltip" title="@lang('project::lang.toggle_invoice_task_description')"></i>
					        </button>
					    </span>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<input type="text" name="rate[]" class="form-control rate input_number" required>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<input type="text" name="quantity[]" class="form-control quantity input_number" required>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<select name="tax_rate_id[]" class="form-control tax">
						<option value="">--</option>
						@foreach($taxes as $id => $tax)
						    <option value="{{ $id }}"
						        @if(isset($tax_attributes[$id]))
						            @foreach($tax_attributes[$id] as $attr => $val)
						                {{ $attr }}="{{ $val }}"
						            @endforeach
						        @endif
						    >{{ $tax }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<input type="text" name="total[]" class="form-control total input_number" required readonly>
				</div>
			</div>
			<div class="col-md-1">
				<i class="fa fa-trash text-danger cursor-pointer remove_this_row"></i>
			</div>
			<div class="col-md-11">
				<div class="form-group description" style="display: none;">
					<textarea name="description[]" class="form-control" placeholder="{{ __('lang_v1.description') }}" rows="3"></textarea>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ action('ExpenseController@store') }}" method="POST" id="add_expense_modal_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang( 'expense.add_expense' )</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if(count($business_locations) == 1)
                        @php 
                            $default_location = current(array_keys($business_locations->toArray())) 
                        @endphp
                    @else
                        @php $default_location = request()->input('location_id'); @endphp
                    @endif
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="expense_location_id">{{ __('purchase.business_location') }}:*</label>
                            <select name="location_id" id="expense_location_id" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($business_locations as $id => $name)
                                    <option value="{{ $id }}" {{ old('location_id', $default_location) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="expense_category_id">{{ __('expense.expense_category') }}:</label>
                            <select name="expense_category_id" id="expense_category_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($expense_categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('expense_category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="expense_ref_no">{{ __('purchase.ref_no') }}:</label>
                            <input type="text" name="ref_no" id="expense_ref_no" class="form-control" value="{{ old('ref_no') }}">
                            <p class="help-block">
                                @lang('lang_v1.leave_empty_to_autogenerate')
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="expense_transaction_date">{{ __('messages.date') }}:*</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="transaction_date" id="expense_transaction_date" class="form-control" value="{{ old('transaction_date', @format_datetime('now')) }}" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="expense_for">{{ __('expense.expense_for') }}:</label> @show_tooltip(__('tooltip.expense_for'))
                            <select name="expense_for" id="expense_for" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ old('expense_for') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expense_tax_id">{{ __('product.applicable_tax') }}:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                <select name="tax_id" id="expense_tax_id" class="form-control">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach($taxes['tax_rates'] as $id => $name)
                                        <option value="{{ $id }}" {{ old('tax_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="expense_final_total">{{ __('sale.total_amount') }}:*</label>
                            <input type="text" name="final_total" id="expense_final_total" class="form-control input_number" placeholder="{{ __('sale.total_amount') }}" value="{{ old('final_total') }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="expense_additional_notes">{{ __('expense.expense_note') }}:</label>
                            <textarea name="additional_notes" id="expense_additional_notes" class="form-control" rows="3">{{ old('additional_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="payment_row">
                    <h4>@lang('purchase.add_payment'):</h4>
                    @include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true])
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <strong>@lang('purchase.payment_due'):</strong>
                                <span id="expense_payment_due">{{@num_format(0)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>
        </form>
    </div>
</div>

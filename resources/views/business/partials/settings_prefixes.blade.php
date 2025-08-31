<div class="pos-tab-content">
     <div class="row">
       

        <div class="col-sm-4">
    <div class="form-group">
        <label for="ref_no_prefixes_purchase">@lang('lang_v1.purchase_order'):</label>
        <input type="text" name="ref_no_prefixes[purchase]" id="ref_no_prefixes_purchase" class="form-control"
            value="{{ $business->ref_no_prefixes['purchase'] ?? '' }}">
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="ref_no_prefixes_purchase_return">@lang('lang_v1.purchase_return'):</label>
        <input type="text" name="ref_no_prefixes[purchase_return]" id="ref_no_prefixes_purchase_return" class="form-control"
            value="{{ $business->ref_no_prefixes['purchase_return'] ?? '' }}">
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="ref_no_prefixes_stock_transfer">@lang('lang_v1.stock_transfer'):</label>
        <input type="text" name="ref_no_prefixes[stock_transfer]" id="ref_no_prefixes_stock_transfer" class="form-control"
            value="{{ $business->ref_no_prefixes['stock_transfer'] ?? '' }}">
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="ref_no_prefixes_stock_adjustment">@lang('stock_adjustment.stock_adjustment'):</label>
        <input type="text" name="ref_no_prefixes[stock_adjustment]" id="ref_no_prefixes_stock_adjustment" class="form-control"
            value="{{ $business->ref_no_prefixes['stock_adjustment'] ?? '' }}">
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="ref_no_prefixes_sell_return">@lang('lang_v1.sell_return'):</label>
        <input type="text" name="ref_no_prefixes[sell_return]" id="ref_no_prefixes_sell_return" class="form-control"
            value="{{ $business->ref_no_prefixes['sell_return'] ?? '' }}">
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="ref_no_prefixes_expense">@lang('expense.expenses'):</label>
        <input type="text" name="ref_no_prefixes[expense]" id="ref_no_prefixes_expense" class="form-control"
            value="{{ $business->ref_no_prefixes['expense'] ?? '' }}">
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="ref_no_prefixes_contacts">@lang('contact.contacts'):</label>
        <input type="text" name="ref_no_prefixes[contacts]" id="ref_no_prefixes_contacts" class="form-control"
            value="{{ $business->ref_no_prefixes['contacts'] ?? '' }}">
    </div>
</div>

    </div>
</div>
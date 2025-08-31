<div class="pos-tab-content">
    <h4>@lang('business.add_keyboard_shortcuts'):</h4>
    <p class="help-block">@lang('lang_v1.shortcut_help'); @lang('lang_v1.example'): <b>ctrl+shift+b</b>, <b>ctrl+h</b></p>
    <p class="help-block">
        <b>@lang('lang_v1.available_key_names_are'):</b>
        <br> shift, ctrl, alt, backspace, tab, enter, return, capslock, esc, escape, space, pageup, pagedown, end, home, <br>left, up, right, down, ins, del, and plus
    </p>
   


    <div class="row">
    <div class="col-sm-6">
        <table class="table table-striped">
            <tr>
                <th>@lang('business.operations')</th>
                <th>@lang('business.keyboard_shortcut')</th>
            </tr>
            <tr>
                <td>@lang('sale.express_finalize'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][express_checkout]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['express_checkout']) ? $shortcuts['pos']['express_checkout'] : '' }}">
                </td>
            </tr>
            <tr>
                <td>@lang('sale.finalize'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][pay_n_checkout]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['pay_n_checkout']) ? $shortcuts['pos']['pay_n_checkout'] : '' }}">
                </td>
            </tr>
            <tr>
                <td>@lang('sale.draft'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][draft]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['draft']) ? $shortcuts['pos']['draft'] : '' }}">
                </td>
            </tr>
            <tr>
                <td>@lang('messages.cancel'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][cancel]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['cancel']) ? $shortcuts['pos']['cancel'] : '' }}">
                </td>
            </tr>
            <tr>
                <td>@lang('lang_v1.recent_product_quantity'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][recent_product_quantity]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['recent_product_quantity']) ? $shortcuts['pos']['recent_product_quantity'] : '' }}">
                </td>
            </tr>
        </table>
    </div>

    <div class="col-sm-6">
        <table class="table table-striped">
            <tr>
                <th>@lang('business.operations')</th>
                <th>@lang('business.keyboard_shortcut')</th>
            </tr>
            <tr>
                <td>@lang('sale.edit_discount'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][edit_discount]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['edit_discount']) ? $shortcuts['pos']['edit_discount'] : '' }}">
                </td>
            </tr>
            <tr>
                <td>@lang('sale.edit_order_tax'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][edit_order_tax]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['edit_order_tax']) ? $shortcuts['pos']['edit_order_tax'] : '' }}">
                </td>
            </tr>
            <tr>
                <td>@lang('sale.add_payment_row'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][add_payment_row]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['add_payment_row']) ? $shortcuts['pos']['add_payment_row'] : '' }}">
                </td>
            </tr>
            <tr>
                <td>@lang('sale.finalize_payment'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][finalize_payment]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['finalize_payment']) ? $shortcuts['pos']['finalize_payment'] : '' }}">
                </td>
            </tr>
            <tr>
                <td>@lang('lang_v1.add_new_product'):</td>
                <td>
                    <input type="text" name="shortcuts[pos][add_new_product]" class="form-control"
                        value="{{ !empty($shortcuts['pos']['add_new_product']) ? $shortcuts['pos']['add_new_product'] : '' }}">
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <h4>@lang('lang_v1.pos_settings'):</h4>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="pos_settings[disable_pay_checkout]" class="input-icheck"
                        value="1" {{ !empty($pos_settings['disable_pay_checkout']) ? 'checked' : '' }}>
                    @lang('lang_v1.disable_pay_checkout')
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="pos_settings[disable_draft]" class="input-icheck"
                        value="1" {{ !empty($pos_settings['disable_draft']) ? 'checked' : '' }}>
                    @lang('lang_v1.disable_draft')
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="pos_settings[disable_express_checkout]" class="input-icheck"
                        value="1" {{ !empty($pos_settings['disable_express_checkout']) ? 'checked' : '' }}>
                    @lang('lang_v1.disable_express_checkout')
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="pos_settings[hide_product_suggestion]" class="input-icheck"
                        value="1" {{ !empty($pos_settings['hide_product_suggestion']) ? 'checked' : '' }}>
                    @lang('lang_v1.hide_product_suggestion')
                </label>
            </div>
        </div>
    </div>
</div>

  

    <hr>
    @include('business.partials.settings_weighing_scale')
</div>
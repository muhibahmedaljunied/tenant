<div class="modal-dialog" role="document">
 <div class="modal-content">

    <form action="{{ action('WarrantyController@store') }}" method="POST" id="warranty_form">
        @csrf

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('lang_v1.add_warranty')</h4>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label for="name">@lang('lang_v1.name'):</label>
                <input type="text" name="name" id="name" class="form-control" required placeholder="@lang('lang_v1.name')">
            </div>

            <div class="form-group">
                <label for="description">@lang('lang_v1.description'):</label>
                <textarea name="description" id="description" class="form-control" placeholder="@lang('lang_v1.description')" rows="3"></textarea>
            </div>

            <strong><label for="duration">@lang('lang_v1.duration'):</label></strong>
            <div class="form-group">
                <input type="number" name="duration" id="duration" class="form-control width-40 pull-left"
                    placeholder="@lang('lang_v1.duration')" required>

                <select name="duration_type" id="duration_type" class="form-control width-60 pull-left" required>
                    <option value="" disabled selected>@lang('messages.please_select')</option>
                    <option value="days">@lang('lang_v1.days')</option>
                    <option value="months">@lang('lang_v1.months')</option>
                    <option value="years">@lang('lang_v1.years')</option>
                </select>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </form>

</div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->

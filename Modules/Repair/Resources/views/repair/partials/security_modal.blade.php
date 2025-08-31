<div class="modal fade register_details_modal" tabindex="-1" role="dialog" 
  aria-labelledby="gridSystemModalLabel" id="security_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">@lang('repair::lang.security')</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="repair_security_pwd">{{ __('lang_v1.password') . ':' }}</label>
                    <input type="text" name="repair_security_pwd" id="repair_security_pwd" class="form-control" placeholder="{{ __('lang_v1.password') }}">
                </div>
                <div class="form-group">
                    <label for="repair_security_pattern">{{ __('repair::lang.pattern') . ':' }}</label>
                    <div id="pattern_container"></div>
                    <input type="hidden" name="repair_security_pattern" id="repair_security_pattern">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
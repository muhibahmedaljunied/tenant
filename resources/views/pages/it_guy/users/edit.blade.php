<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('ItGuy\UserController@update', [$user->id]) }}" method="POST" id="user_edit_form">
            @csrf
 

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('user.edit_user')</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="actual_name">{{ __('user.name') }}:*</label>
                        <input type="text" name="actual_name" value="{{ $user->name }}" class="form-control"
                            required placeholder="{{ __('user.name') }}">
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="short_name">{{ __('user.short_name') }}:*</label>
                        <input type="text" name="short_name" value="{{ $user->code }}" class="form-control"
                            required placeholder="{{ __('user.short_name') }}">
                    </div>







                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

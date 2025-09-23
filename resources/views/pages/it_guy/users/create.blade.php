<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('ItGuy\UserController@store') }}" method="POST" id="user_add_form"
            enctype="multipart/form-data">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('user.add_user')</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        @component('components.widget')
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="surname">@lang('business.prefix'):</label>
                                    <input type="text" name="surname" id="surname" class="form-control"
                                        placeholder="@lang('business.prefix_placeholder')">
                                </div>
                            </div>
        
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="first_name">@lang('business.first_name'):</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" required
                                        placeholder="@lang('business.first_name')">
                                </div>
                            </div>
        
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="last_name">@lang('business.last_name'):</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                        placeholder="@lang('business.last_name')">
                                </div>
                            </div>
        
                            <div class="clearfix"></div>
        
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">@lang('business.email'):</label>
                                    <input type="text" name="email" id="email" class="form-control" required
                                        placeholder="@lang('business.email')">
                                </div>
                            </div>
        
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <br />
                                        <label>
                                            <input type="checkbox" name="is_active" value="active" class="input-icheck status" checked>
                                            @lang('lang_v1.status_for_user')
                                        </label>
                                        @show_tooltip(__('lang_v1.tooltip_enable_user_active'))
                                    </div>
                                </div>
                            </div>
                        @endcomponent
                    </div>
                  
        
        
             
        
        
                </div>

            </div>

            <div class="modal-footer">
                {{-- <button type="submit" class="btn btn-primary">@lang('messages.save')</button> --}}
                <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

        </form>

    </div>
</div>


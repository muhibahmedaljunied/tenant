<div class="modal-dialog  modal-lg" role="document">
    <form action="{{ action('\\Modules\\Project\\Http\\Controllers\\ProjectController@store') }}" id="project_form" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    @lang('project::lang.create_project')
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       <div class="form-group">
                            <label for="name">{{ __('messages.name') }}:*</label>
                            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">{{ __('lang_v1.description') }}:</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            <label for="contact_id">{{ __('role.customer') }}:</label>
                            <select name="contact_id" id="contact_id" class="form-control select2" style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($customers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                       </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">{{ __('sale.status') }}:*</label>
                            <select name="status" id="status" class="form-control select2" required style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($statuses as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lead_id">{{ __('project::lang.lead') }}:*</label>
                            <select name="lead_id" id="lead_id" class="form-control select2" required style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                            <label for="start_date">{{ __('business.start_date') }}:</label>
                            <input type="text" name="start_date" id="start_date" class="form-control datepicker" readonly value="{{ old('start_date') }}">
                       </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">{{ __('project::lang.end_date') }}:</label>
                            <input type="text" name="end_date" id="end_date" class="form-control datepicker" readonly value="{{ old('end_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">{{ __('project::lang.members') }}:*</label>
                            <select name="user_id[]" id="user_id" class="form-control select2" multiple required style="width: 100%;">
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">{{ __('project::lang.category') }}:</label>
                            <select name="category_id[]" id="category_id" class="form-control select2" multiple style="width: 100%;">
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm ladda-button" data-style="expand-right">
                    <span class="ladda-label">@lang('messages.save')</span>
                </button>
                 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
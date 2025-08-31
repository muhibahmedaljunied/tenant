<div class="modal-dialog modal-lg" role="document">
    <form action="{{ action('\\Modules\\Project\\Http\\Controllers\\TaskController@store') }}" id="project_task_form" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            <h4 class="modal-title">
                @lang('project::lang.create_task')
            </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       <div class="form-group">
                            <label for="subject">{{ __('project::lang.subject') }}:*</label>
                            <input type="text" name="subject" id="subject" class="form-control" required value="{{ old('subject') }}">
                       </div>
                    </div>
                </div>
                <input type="hidden" name="project_id" value="{{ $project_id }}" class="form-control">
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
                            <label for="start_date">{{ __('business.start_date') }}:</label>
                            <input type="text" name="start_date" id="start_date" class="form-control datepicker" readonly value="{{ old('start_date') }}">
                       </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="due_date">{{ __('project::lang.due_date') }}:</label>
                            <input type="text" name="due_date" id="due_date" class="form-control datepicker" readonly value="{{ old('due_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="priority">{{ __('project::lang.priority') }}:*</label>
                            <select name="priority" id="priority" class="form-control select2" required style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($priorities as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                            <label for="user_id">{{ __('project::lang.members') }}:*</label>
                            <select name="user_id[]" id="user_id" class="form-control select2" multiple required style="width: 100%;">
                                @foreach($project_members as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="custom_field_1">{{ __('project::lang.task_custom_field_1') }}:</label>
                            <input type="text" name="custom_field_1" id="custom_field_1" class="form-control" value="{{ old('custom_field_1') }}">
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="custom_field_2">{{ __('project::lang.task_custom_field_2') }}:</label>
                            <input type="text" name="custom_field_2" id="custom_field_2" class="form-control" value="{{ old('custom_field_2') }}">
                       </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="custom_field_3">{{ __('project::lang.task_custom_field_3') }}:</label>
                            <input type="text" name="custom_field_3" id="custom_field_3" class="form-control" value="{{ old('custom_field_3') }}">
                       </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="custom_field_4">{{ __('project::lang.task_custom_field_4') }}:</label>
                            <input type="text" name="custom_field_4" id="custom_field_4" class="form-control" value="{{ old('custom_field_4') }}">
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
        </div>
    </form>
</div>
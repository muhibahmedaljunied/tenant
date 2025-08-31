<div class="modal-dialog" role="document">
    <form action="{{ action('\\Modules\\Project\\Http\\Controllers\\ProjectTimeLogController@update', $project_task_time_log->id) }}" id="time_log_form" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    @lang('project::lang.edit_time_log')
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="project_task_id">{{ __('project::lang.task') }}:</label>
                            <select name="project_task_id" id="project_task_id" class="form-control select2" style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($project_tasks as $id => $name)
                                    <option value="{{ $id }}" {{ $project_task_time_log->project_task_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="project_id" value="{{ $project_task_time_log->project_id }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_datetime">{{ __('project::lang.start_date_time') }}:*</label>
                            <input type="text" name="start_datetime" id="start_datetime" class="form-control datetimepicker" required readonly value="{{ @format_datetime($project_task_time_log->start_datetime) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_datetime">{{ __('project::lang.end_date_time') }}:*</label>
                            <input type="text" name="end_datetime" id="end_datetime" class="form-control datetimepicker" required readonly value="{{ @format_datetime($project_task_time_log->end_datetime) }}">
                        </div>
                    </div>
                    @if($is_lead_or_admin)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_id">{{ __('role.user') }}:*</label>
                                <select name="user_id" id="user_id" class="form-control select2" required style="width: 100%;">
                                    <option value="">{{ __('messages.please_select') }}</option>
                                    @foreach($project_members as $id => $name)
                                        <option value="{{ $id }}" {{ $project_task_time_log->user_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="note">{{ __('brand.note') }}:</label>
                            <textarea name="note" id="note" class="form-control" rows="4">{{ $project_task_time_log->note }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    @lang('messages.update')
                </button>
                 <button type="button" class="btn btn-dafualt btn-sm" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </div>
    </form>
</div>
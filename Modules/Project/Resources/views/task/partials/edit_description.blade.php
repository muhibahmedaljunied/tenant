<div class="task_description toggle_description_fields">
    {!! $project_task->description !!}
</div>
<!-- form open -->
<form action="{{ action('\\Modules\\Project\\Http\\Controllers\\TaskController@postTaskDescription', ['id' => $project_task->id, 'project_id' => $project_task->project_id]) }}" id="update_task_description" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <textarea name="description" id="edit_description_of_task" class="form-control">{{ $project_task->description }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary btn-sm ladda-button save-description-btn" data-style="expand-right">
        <span class="ladda-label">@lang('messages.update')</span>
    </button>
     <button type="button" class="btn btn-default btn-sm close_update_task_description_form">
        @lang('messages.close')
    </button>
</form>
<!-- /form close -->
<div class="modal-dialog" role="document">
    <form action="{{ action('\\Modules\\Project\\Http\\Controllers\\TaskController@postTaskStatus', $project_task->id) }}" id="change_status" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    @lang("project::lang.change_status")
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="status">{{ __('sale.status') }}:*</label>
                            <select name="status" id="status" class="form-control select2" required style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($statuses as $id => $name)
                                    <option value="{{ $id }}" {{ $project_task->status == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="project_id" value="{{ $project_task->project_id }}" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    @lang('messages.update')
                </button>
                 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
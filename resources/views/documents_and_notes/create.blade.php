<div class="modal-dialog modal-lg" role="document">
    <form action="{{ action('DocumentAndNoteController@store') }}" id="docus_notes_form" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    @lang('lang_v1.add_note')
                </h4>
            </div>
            <!-- model id like project_id, user_id -->
            <input type="hidden" name="notable_id" value="{{ $notable_id }}" class="form-control">
            <!-- model name like App\User -->
            <input type="hidden" name="notable_type" value="{{ $notable_type }}" class="form-control">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       <div class="form-group">
                            <label for="heading">{{ __('lang_v1.heading') }}:*</label>
                            <input type="text" name="heading" id="heading" class="form-control" required>
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">{{ __('lang_v1.description') }}:</label>
                            <textarea name="description" id="docs_note_description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fileupload">
                                @lang('lang_v1.documents'):
                            </label>
                            <div class="dropzone" id="docusUpload"></div>
                        </div>
                        <input type="hidden" id="docus_notes_media" name="file_name[]" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_private" value="1"> @lang('lang_v1.is_private')
                                    <i class="fa fa-info-circle" data-toggle="tooltip" title="@lang('lang_v1.note_will_be_visible_to_u_only')"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    @lang('messages.save')
                </button>
                 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
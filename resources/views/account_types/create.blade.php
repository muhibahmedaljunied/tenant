<div class="modal-dialog" role="document">
  	<div class="modal-content">

    <form action="{{ action('AccountTypeController@store') }}" method="POST" id="account_type_form">
      @csrf
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'lang_v1.add_account_type' )</h4>
    </div>

    <div class="modal-body">
      	<div class="form-group">
          <label for="name">{{ __( 'lang_v1.name' ) . ':*' }}</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'lang_v1.name' ) }}">
        </div>

      <div class="form-group">
          <label for="parent_account_type_id">{{ __( 'lang_v1.parent_account_type' ) . ':' }}</label>
          <select name="parent_account_type_id" id="parent_account_type_id" class="form-control">
            <option value="">{{ __( 'messages.please_select' ) }}</option>
            @foreach($account_types as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

  	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
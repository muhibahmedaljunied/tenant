<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('ExpenseCategoryController@update', [$expense_category->id]) }}" method="POST" id="expense_category_add_form">
      @csrf
      @method('PUT')

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'expense.edit_expense_category' )</h4>
    </div>

    <div class="modal-body">
     <div class="form-group">
        <label for="name">{{ __( 'expense.category_name' ) }}:*</label>
        <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __( 'expense.category_name' ) }}" value="{{ old('name', $expense_category->name) }}">
      </div>

      <div class="form-group">
        <label for="code">{{ __( 'expense.category_code' ) }}:</label>
        <input type="text" name="code" id="code" class="form-control" placeholder="{{ __( 'expense.category_code' ) }}" value="{{ old('code', $expense_category->code) }}">
      </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
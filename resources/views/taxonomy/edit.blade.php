<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ action('TaxonomyController@update', [$category->id]) }}" method="POST" id="category_edit_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'messages.edit' )</h4>
      </div>

      <div class="modal-body">
        @php
        $name_label = !empty($module_category_data['taxonomy_label']) ? $module_category_data['taxonomy_label'] : __( 'category.category_name' );
        $cat_code_enabled = isset($module_category_data['enable_taxonomy_code']) && !$module_category_data['enable_taxonomy_code'] ? false : true;

        $cat_code_label = !empty($module_category_data['taxonomy_code_label']) ? $module_category_data['taxonomy_code_label'] : __( 'category.code' );

        $enable_sub_category = isset($module_category_data['enable_sub_taxonomy']) && !$module_category_data['enable_sub_taxonomy'] ? false : true;

        $category_code_help_text = !empty($module_category_data['taxonomy_code_help_text']) ? $module_category_data['taxonomy_code_help_text'] : __('lang_v1.category_code_help');
        @endphp
        <div class="form-group">
          <label for="name">{{ $name_label }}:*</label>
          <input type="text" name="name" value="{{ $category->name }}" class="form-control" required placeholder="{{ $name_label }}">
        </div>

        @if($cat_code_enabled)
        <div class="form-group">
          <label for="short_code">{{ $cat_code_label }}:</label>
          <input type="text" name="short_code" value="{{ $category->short_code }}" class="form-control" placeholder="{{ $cat_code_label }}">
          <p class="help-block">{!! $category_code_help_text !!}</p>
        </div>
        @endif

        <div class="form-group">
          <label for="description">{{ __('lang_v1.description') }}:</label>
          <textarea name="description" class="form-control" rows="3" placeholder="{{ __('lang_v1.description') }}">{{ $category->description }}</textarea>
        </div>

        @if(!empty($parent_categories) && $enable_sub_category)
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="add_as_sub_cat" value="1" class="toggler" data-toggle_id="parent_cat_div" {{ !$is_parent ? 'checked' : '' }}>
              {{ __('lang_v1.add_as_sub_txonomy') }}
            </label>
          </div>
        </div>

        <div class="form-group {{ $is_parent ? 'hide' : '' }}" id="parent_cat_div">
          <label for="parent_id">{{ __('lang_v1.select_parent_taxonomy') }}:</label>
          <select name="parent_id" class="form-control">
            @foreach($parent_categories as $key => $value)
            <option value="{{ $key }}" {{ $selected_parent == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
          </select>
        </div>
        @endif

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('\Modules\Manufacturing\Http\Controllers\RecipeController@addIngredients') }}"
            method="GET"
            id="choose_product_form">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang( 'manufacturing::lang.choose_product' )</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="variation_id">{{ __('manufacturing::lang.choose_product') }}:</label>
                    <select name="variation_id" id="variation_id" class="form-control" required style="width: 100%;">
                        <option value="">{{ __('messages.please_select') }}</option>
                        {{-- Options to be loaded dynamically --}}
                    </select>
                </div>

                <div class="form-group" id="recipe_selection">
                    <label for="copy_recipe_id">{{ __('manufacturing::lang.copy_from_recipe') }}:</label>
                    <select name="copy_recipe_id" class="form-control" style="width: 100%;">
                        <option value="">{{ __('lang_v1.none') }}</option>
                        @foreach($recipes as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
                <button type="submit" class="btn btn-primary">@lang( 'manufacturing::lang.continue' )</button>
            </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
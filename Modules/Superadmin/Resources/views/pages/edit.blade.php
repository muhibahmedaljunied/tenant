@extends('layouts.app')
@section('title', __('superadmin::lang.superadmin') . ' | ' . __('superadmin::lang.edit_page'))

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('superadmin::lang.edit_page')</h1>
</section>

<!-- Main content -->
<section class="content">

	<form action="{{ action('\\Modules\\Superadmin\\Http\\Controllers\\PageController@update', $page->id) }}" method="POST" id="add_page_form">
		@csrf
		@method('PUT')
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="title">{{ __('superadmin::lang.page_title') }}:</label>
							<input type="text" name="title" id="title" class="form-control" placeholder="{{ __('superadmin::lang.page_title') }}" value="{{ old('title', $page->title) }}">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="slug">{{ __('superadmin::lang.slug') }}:</label>
							<input type="text" name="slug" id="slug" class="form-control" placeholder="{{ __('superadmin::lang.slug') }}" value="{{ old('slug', $page->slug) }}" required>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="menu_order">{{ __('superadmin::lang.menu_order') }}:</label> @show_tooltip(__('superadmin::lang.menu_order_tooltip'))
							<input type="number" name="menu_order" id="menu_order" class="form-control" placeholder="{{ __('superadmin::lang.menu_order') }}" value="{{ old('menu_order', $page->menu_order) }}">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="is_shown" value="1" class="input-icheck" {{ old('is_shown', !empty($page->is_shown)) ? 'checked' : '' }}>
                        {{__('superadmin::lang.is_visible')}}
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="content">{{ __('superadmin::lang.page_content') }}:</label>
							<textarea name="content" id="content" class="form-control" placeholder="{{ __('superadmin::lang.page_content') }}">{{ old('content', $page->content) }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<button type="submit" class="btn btn-primary pull-right btn-flat">@lang('messages.save')</button>
			</div>
		</div>

	</form>
</section>

@endsection

@section('javascript')
	@include('superadmin::pages.form_script')
@endsection
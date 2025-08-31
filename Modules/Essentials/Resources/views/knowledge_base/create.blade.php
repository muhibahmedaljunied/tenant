@extends('layouts.app')

@php
    $page_title = __( 'essentials::lang.add_knowledge_base' );
    $kb_type = '';
    if(!empty($parent)) {
        $kb_type = $parent->kb_type == 'knowledge_base' ? 'section' : 'article';
    }
    if($kb_type == 'section') {
        $page_title = __( 'essentials::lang.add_section' );
    } else if($kb_type == 'article') {
        $page_title = __( 'essentials::lang.add_article' );
    }
@endphp
@section('title', $page_title)

@section('content')
@include('essentials::layouts.nav_essentials')

<!-- Main content -->
<section class="content">
<form action="{{ action('\\Modules\\Essentials\\Http\\Controllers\\KnowledgeBaseController@store') }}" method="post">
    @csrf
    @if(!empty($parent))
        <input type="hidden" name="kb_type" value="{{ $kb_type }}">
        <input type="hidden" name="parent_id" value="{{ $parent->id }}">
    @endif
    @component('components.widget', ['title' => $page_title])
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="title">{{ __( 'essentials::lang.title' ) . ':*' }}</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="{{ __( 'essentials::lang.title' ) }}" required value="{{ old('title') }}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="content">{{ __( 'essentials::lang.content' ) . ':' }}</label>
                    <textarea name="content" id="content" class="form-control" placeholder="{{ __( 'essentials::lang.content' ) }}">{{ old('content') }}</textarea>
                </div>
            </div>
            @if(empty($parent))
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="share_with">{{ __( 'essentials::lang.share_with' ) . ':' }}</label>
                        <select name="share_with" id="share_with" class="form-control select2">
                            <option value="public" {{ old('share_with', 'public') == 'public' ? 'selected' : '' }}>{{ __('essentials::lang.public') }}</option>
                            <option value="private" {{ old('share_with') == 'private' ? 'selected' : '' }}>{{ __( 'essentials::lang.private' ) }}</option>
                            <option value="only_with" {{ old('share_with') == 'only_with' ? 'selected' : '' }}>{{ __( 'essentials::lang.only_with' ) }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6" id="user_ids_div" style="display: none;">
                    <div class="form-group">
                        <label for="user_ids">{{ __( 'essentials::lang.share_only_with' ) . ':' }}</label>
                        <select name="user_ids[]" id="user_ids" class="form-control select2" multiple style="width: 100%;">
                            @foreach($users as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right">@lang( 'messages.save' )</button>
            </div>
        </div>
    @endcomponent
</form>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).ready( function(){
        init_tinymce('content');

        $('#share_with').change( function() {
            if ($(this).val() == 'only_with') {
                $('#user_ids_div').fadeIn();
            } else {
                $('#user_ids_div').fadeOut();
            }
        });
    });
</script>
@endsection

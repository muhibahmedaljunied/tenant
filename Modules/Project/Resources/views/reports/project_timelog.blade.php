@extends('layouts.app')
@section('title', __('project::lang.project_report'))

@section('content')
@include('project::layouts.nav')
	<section class="content-header">
		<h1>
	    	@lang('report.reports')
	    	<small>
	    		@lang('project::lang.time_logs') @lang('project::lang.by_projects')
	    	</small>
	    </h1>
	</section>
	<section class="content">
	    @component('components.filters', ['title' => __('report.filters')])
			<div class="row">
	            <div class="col-md-4">
	                <div class="form-group">
	                    <label for="project_timelog_report_project_id">{{ __('project::lang.project') }}:</label>
	                    <select name="project_id[]" id="project_timelog_report_project_id" class="form-control select2" multiple style="width: 100%;">
	                        @foreach($projects as $id => $name)
	                            <option value="{{ $id }}">{{ $name }}</option>
	                        @endforeach
	                    </select>
	                </div>    
	            </div>
	            <div class="col-md-4">
	                <div class="form-group">
	                    <label for="project_timelog_report_daterange">{{ __('report.date_range') }}:</label>
	                    <input type="text" name="date_range" id="project_timelog_report_daterange" class="form-control" placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
	                </div>
	            </div>
	        </div>
	    @endcomponent
	    <div class="box box-solid">
	    	<div class="box-body project_timelog_report"></div>
	    </div>
	</section>
@endsection
@section('javascript')
<script src="{{ asset('modules/project/js/project.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	getProjectTimeLogReport();
    });
</script>
@endsection
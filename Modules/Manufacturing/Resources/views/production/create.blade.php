@extends('layouts.app')
@section('title', __('manufacturing::lang.production'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('manufacturing::lang.production') </h1>
</section>

<!-- Main content -->
<section class="content">
    <form action="http://127.0.0.1:8000/manufacturing/production" method="POST" id="production_form" enctype="multipart/form-data">
        
        <div class="box box-primary">
            <div class="row">
                <div class="col-sm-3">
                    <label for="ref_no"> __('Reference Number'):</label>
                    <input type="text" name="ref_no" id="ref_no" class="form-control">
                </div>
                <div class="col-sm-3">
                    <label for="transaction_date">Manufacturing Date:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="transaction_date" id="transaction_date" class="form-control" readonly required value="now">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="location_id">Business Location:</label>
                    <select name="location_id" class="form-control select2" required>
                        <option value="">Please Select</option>
                        <!-- Populate options dynamically -->
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="variation_id">Product:</label>
                    <select name="variation_id" class="form-control select2" required>
                        <option value="">Please Select</option>
                        <!-- Populate options dynamically -->
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="recipe_quantity">Quantity:</label>
                    <div class="input-group">
                        <input type="text" name="quantity" id="recipe_quantity" class="form-control input_number" required value="1">
                        <span class="input-group-addon">Units</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <h3>Ingredients</h3>
            <div class="row">
                <div class="col-md-12">
                    <div id="enter_ingredients_table" class="text-center">
                        <i>Add Ingredients Here</i>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-3">
                    <label for="lot_number">Lot Number:</label>
                    <input type="text" name="lot_number" class="form-control">
                </div>
                <div class="col-sm-3">
                    <label for="exp_date">Expiration Date:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="exp_date" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="mfg_wasted_units">Waste Units:</label>
                    <div class="input-group">
                        <input type="text" name="mfg_wasted_units" class="form-control input_number" value="0">
                        <span class="input-group-addon">Units</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="production_cost">Production Cost:</label>
                    <div class="input-group">
                        <input type="text" name="production_cost" class="form-control input_number" value="0">
                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-offset-9">
                    <strong>Total Production Cost:</strong>
                    <span id="total_production_cost">0</span><br>
                    <strong>Total Cost:</strong>
                    <span id="final_total_text">0</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-offset-9">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="finalize" value="1"> Finalize Production
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
            </div>
        </div>

    </form>
</section>

@endsection

@section('javascript')
	@include('manufacturing::production.production_script')
@endsection

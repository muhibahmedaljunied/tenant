@extends('layouts.app')
@section('title', 'جرد المخازن')

@section('content')
<style>
    .span_success{
        color: #A60C0C;
        font-size: 17px;
        font-weight: bold;
    }

</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>  جرد المخزن :
      {{$transaction->name}}
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <input type="hidden" id="transaction_id" value="{{$transaction_id}}">
    <input type="hidden" id="location_id" value="{{$location_id}}">

    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
                <div class="col-md-3" >
                    <div class="form-group">
                        <label for="type">{{ __('product.product_type') . ':' }}</label>
                        <select name="type" id="product_list_filter_type" class="form-control select2" style="width:100%">
                            <option value="">{{ __('lang_v1.all') }}</option>
                            <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>{{ __('lang_v1.single') }}</option>
                            <option value="variable" {{ old('type') == 'variable' ? 'selected' : '' }}>{{ __('lang_v1.variable') }}</option>
                            <option value="combo" {{ old('type') == 'combo' ? 'selected' : '' }}>{{ __('lang_v1.combo') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="category_id">{{ __('product.category') . ':' }}</label>
                        <select name="category_id" id="product_list_filter_category_id" class="form-control select2" style="width:100%">
                            <option value="">{{ __('lang_v1.all') }}</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ old('category_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3"  >
                    <div class="form-group">
                        <label for="unit_id">{{ __('product.unit') . ':' }}</label>
                        <select name="unit_id" id="product_list_filter_unit_id" class="form-control select2" style="width:100%">
                            <option value="">{{ __('lang_v1.all') }}</option>
                            @foreach($units as $key => $value)
                                <option value="{{ $key }}" {{ old('unit_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3" >
                    <div class="form-group">
                        <label for="brand_id">{{ __('product.brand') . ':' }}</label>
                        <select name="brand_id" id="product_list_filter_brand_id" class="form-control select2" style="width:100%">
                            <option value="">{{ __('lang_v1.all') }}</option>
                            @foreach($brands as $key => $value)
                                <option value="{{ $key }}" {{ old('brand_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3" >
                    <div class="form-group">
                        <label for="product_list_filter_current_stock">{{ __('report.current_stock') . ':' }}</label>
                        <select name="current_stock" id="product_list_filter_current_stock" class="form-control select2" style="width:100%">
                            <option value="">{{ __('lang_v1.all') }}</option>
                            <option value="zero" {{ old('current_stock') == 'zero' ? 'selected' : '' }}>Zero</option>
                            <option value="gtzero" {{ old('current_stock') == 'gtzero' ? 'selected' : '' }}>اكبر من الصفر</option>
                            <option value="lszero" {{ old('current_stock') == 'lszero' ? 'selected' : '' }}>اقل من الصفر</option>
                        </select>
                    </div>
                </div>
               <div class="col-md-3 hidden">
                    <div class="form-group">
                        <label for="price">{{ __('lang_v1.default_selling_price') . ':' }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control " id="default_selling_price" name="default_selling_price" value="">
                            <span class="input-group-btn">
                        <button type="button" class="btn btn-default bg-white btn-flat btn-modal"  title="@lang('unit.add_unit')" ><i class="fa fa-search text-primary fa-lg"></i></button>
                    </span>
                        </div>

                    </div>
                </div>
                <div class="col-md-3" >
                    <div class="form-group">
                        <label for="stock_status">{{ __('inventory::lang.stoking_status') . ':' }}</label>
                         <select class="form-control select2" name="stock_status" id="stock_status">
                            <option value="0"> الكل</option>
                            <option value="1" {{ old('stock_status') == '1' ? 'selected' : '' }}>@lang('inventory::lang.stoking_product')</option>
                            <option value="2" {{ old('stock_status') == '2' ? 'selected' : '' }}>@lang('inventory::lang.not_stoking_product')</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 hidden " id="filter_date_range">
                    <div class="form-group">
                        <label for="sell_list_filter_date_range">{{ __('report.date_range') . ':' }}</label>
                        <input type="text" name="sell_list_filter_date_range" id="sell_list_filter_date_range" placeholder="{{ __('lang_v1.select_a_date_range') }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="searchtext">إسم المنتج - الكود</label>
                        <div class="input-group" >
                            <span  class="input-group-addon">
                                 <i class="fa fa-search text-primary fa-lg" aria-hidden="true"></i>
                            </span>
                            <input type="text" class="form-control " id="searchtext" name="searchtext" value="">

                        </div>

                        </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label for="pagsize">عدد العناصر</label>
                       <select class="form-control" id="pagsize" name="pagsize">
                           <option value="25">25</option>
                           <option value="50">50</option>
                           <option value="100">100</option>
                           <option value="300">300</option>
                           <option value="999999">الكل</option>
                       </select>
                    </div>
                </div>
            @endcomponent

        </div>
    </div>

    <div  id="products" style="background-color:white;border-radius: 10px;padding:10px 5px">

        <div class="table-responsive" style="background-color: white">
            <table class="table table-bordered table-striped"  style="width: 1300px;margin: auto" >
                <thead>
                <tr>
                    <th>م </th>
                    <th> المنتج</th>
                    <th> الكود</th>
                    @can('inventory.showprice')
                        <th> سعر الشراء</th>
                        <th> سعر البيع</th>
                    @endcan
                    <th> تاريخ الجرد</th>
                    <th> المستخدم</th>
                    <th> الرصيد </th>
                    <th>@lang( 'messages.action' )</th>
                </tr>
                </thead>
                <tbody id="datatablebody">

                </tbody>
            </table>
        </div>
        <div id="getmore" style="margin: auto;margin-top: 10px" >
            <button class="btn btn-success" onclick="getproducts()"> @lang('inventory::lang.get_more') </button>
            <button class="btn btn-danger" onclick="getallproducts()"> @lang('inventory::lang.get_all') </button>
        </div>
    </div>


    <input type="hidden" value="0" id="offset">
    <div class="modal fade user_modal" tabindex="-1" role="dialog"
    	aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade stocking" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">


    </div>


</section>
<!-- /.content -->
@stop
@section('javascript')
<script type="text/javascript">
    $(document).ready( function() {
        //Date range as a button
        $('#sell_list_filter_date_range').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                $('#offset').val(0);
                $('#datatablebody').html('');
                getproducts();
            }
        );
        $('#sell_list_filter_date_range').on('cancel.daterangepicker', function (ev, picker) {
            $('#sell_list_filter_date_range').val('');
            $('#offset').val(0);
            $('#datatablebody').html('');
            getproducts();
        });
    });

    $(document).ready( function(){
        getproducts();

    });

    $("#save_button").on('click',function(){
       var var_id = 0;
       var product_name =$("#variation_id option:selected").html();
       //$('#product_name').val(product_name);
        $.ajax({
               type:'POST',
               url:"{{action('\Modules\Inventory\Http\Controllers\InventoryController@stock_line_save')}}",
               data:{
                     '_token' :"{{ csrf_token() }}",
                     'variation_id':  var_id,
                     'transaction_id':{{$transaction_id}},
                     'real_qty_available': $('#real_qty_available').val(),
               },
               success:function(result) {


                  if(result.success == true){
                        toastr.success(result.msg);
                        get_last_product();
                    } else {
                        toastr.error(result.msg);
                    }
               }
            });

  });

    function getallproducts() {
        $('#pagsize').val(999999).select();
        getproducts();
    }
    function  getproducts() {
        if($('#sell_list_filter_date_range').val()) {
            var start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');

        }
        var offset=$('#offset').val();
        var pagsize=$('#pagsize').val();
        var searchtext=$('#searchtext').val();

        $.ajax({
            type:'get',
            url:"{{action('\Modules\Inventory\Http\Controllers\InventoryController@stocking')}}",
            data: {
                  type : $('#product_list_filter_type').val(),
                  category_id : $('#product_list_filter_category_id').val(),
                   brand_id : $('#product_list_filter_brand_id').val(),
                   unit_id : $('#product_list_filter_unit_id').val(),
                   current_stock:$("#product_list_filter_current_stock").val(),
                   default_selling_price:$("#default_selling_price").val(),
                   id:{{$transaction_id}},
                   stock_status:$('#stock_status').val(),
                   start_date:start,
                   end_date:end,
                   pagsize:pagsize,
                   offset:offset,
                  searchtext:searchtext

            },
            success:function(result) {
                $('#datatablebody').append(result.html_content);
                $('#offset').val(offset*1+pagsize*1);

               if(result.success == true){
                  //  toastr.success(result.msg);
                      } else {
                   // toastr.error(result.msg);
                }
            }
        });
    }

   function get_last_product(){
        $.ajax({
               type:'POST',
               url:"{{action('\Modules\Inventory\Http\Controllers\InventoryController@get_last_product')}}",
               data:{
                     '_token' :"{{ csrf_token() }}",
                     'transaction_id':{{$transaction_id}},

               },
               success:function(result) {

                     $("#lastproduct").val(result);

               }
            });

   }

    $(document).on('change', '#product_list_filter_current_stock,' +
        '#product_list_filter_current_stock,' +
        '#product_list_filter_type, #product_list_filter_category_id, ' +
        '#product_list_filter_brand_id, #product_list_filter_unit_id, #stock_status, '+
        '#pagsize',
        function() {
        if($('#stock_status').val()==1)
            $('#filter_date_range').removeClass('hidden');
           else
            $('#filter_date_range').addClass('hidden');

            $('#datatablebody').html('');
            $('#offset').val(0);
           getproducts();
        });

    $("#searchtext").on("keyup", function() {
        $('#datatablebody').html('');
        $('#offset').val(0);
        var tex=$("#searchtext").val();
        if(tex.length>0)
            getproducts();
            });


    $("#searchtext_old").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#datatablebody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    function savedata2(product_id,variation_id) {
        swal({
            title: LANG.sure,
            text: 'سوف يتم تعديل رصيد المنتج !',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willSave => {
            if (willSave) {
                var new_val = $('#new_' + product_id).val();
                var old_val = $('#old_' + product_id).val();
                $.ajax({
                    type: 'GET',
                    url: "{{action('\Modules\Inventory\Http\Controllers\InventoryController@stock_line_save')}}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        transaction_id:{{$transaction_id}},
                        product_id: product_id,
                        variation_id: variation_id,
                        new_val: new_val,
                        old_val: old_val
                    },
                    success: function (result) {
                        if (result.success) {
                            $('#old_' + product_id).val(new_val);
                            toastr.success(result.msg);
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });


    }

    function savedata(product_id,variation_id){
        var transaction_id=$('#transaction_id').val();
        var location_id=$('#location_id').val();
         $.ajax({
            url:"{{action('\Modules\Inventory\Http\Controllers\InventoryController@getproduct')}}",
            dataType: 'html',
            data:{
                transaction_id:transaction_id
                ,product_id:product_id
                ,variation_id:variation_id
                ,location_id:location_id
            },
            success: function(result) {
                $('.stocking')
                    .html(result)
                    .modal('show');
            },
        });
    }



    $(document).on('submit', 'form#stocking_save', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();
        var variation_id=$('#variation_id').val();
        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success == true) {
                    $('div.stocking').modal('hide');
                    var datetime = new Date();
                    datetime = moment(datetime).format("h:m:s ");
                    $('#update_'+variation_id).text("@lang('inventory::lang.stoking_now')"+"  - "+datetime);
                    $('#update_'+variation_id).addClass('span_success') ;
                    $('#current_stock_'+variation_id).text($('#stock_quantity').val());
                    $('#current_stock_'+variation_id).addClass('span_success') ;

                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

function deleterec(product_id,variation_id){
    var transaction_id=$('#transaction_id').val();
    var location_id=$('#location_id').val();
    swal({
        title: LANG.sure,
        text: 'سوف يتم حذف الجرد !',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willSave => {
        if (willSave) {
            $.ajax({
                url: "{{action('\Modules\Inventory\Http\Controllers\InventoryController@deletstock')}}",
                dataType: 'html',
                data: {
                    transaction_id: transaction_id
                    , product_id: product_id
                    , variation_id: variation_id
                    , location_id: location_id
                },
                success: function (result) {
                    if(result.success== true){
                        toastr.success(result.msg);
                    }else{
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
}
$(document).on('change','#stock_quantity,#unit_price',function () {
    var curent_quantity= __read_number($('#curent_quantity'));
    var stock_quantity= __read_number($('#stock_quantity'));
    var unit_price= __read_number($('#unit_price'));
    var def_quantity=(stock_quantity-curent_quantity)*unit_price;
    __write_number($('#total_price'),def_quantity);
});

    $(document).on('change','#total_price',function () {
        var curent_quantity= __read_number($('#curent_quantity'));
        var stock_quantity= __read_number($('#stock_quantity'));
        var total_price= __read_number($('#total_price'));
        var def_quantity=total_price/(stock_quantity-curent_quantity);
        __write_number($('#unit_price'),def_quantity);
    });




</script>
@endsection

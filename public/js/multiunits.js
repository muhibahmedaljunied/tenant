$('#enable_multi_unit').on('ifChanged', function () {
    let $this = $(this);
    // console.log('changed');
    if ($this.is(':checked')) {
        $('.multi_unit_box').removeClass('hidden');

        if($('.add_another_units_table tbody tr').length == 0){
            $('.add_unit_row')[0].click()
        }
    } else {
        $('.multi_unit_box').addClass('hidden');
    }
});

$(document).on('change', 'input.unit_single_dpp', function (e) {
    var tr_obj = $(this).closest('tr');

    var purchase_exc_tax = __read_number($(this));
    purchase_exc_tax = purchase_exc_tax == undefined ? 0 : purchase_exc_tax;

    var tax_rate = $('select#tax').find(':selected').data('rate');
    tax_rate = tax_rate == undefined ? 0 : tax_rate;

    var purchase_inc_tax = __add_percent(purchase_exc_tax, tax_rate);
    __write_number(tr_obj.find('input.unit_single_dp_inc_tax'), purchase_inc_tax);

    var profit_percent = __read_number(tr_obj.find('input.profit_unit_percent'));
    var selling_price = __add_percent(purchase_exc_tax, profit_percent);
    __write_number(tr_obj.find('input.unit_single_dsp'), selling_price);

    var selling_price_inc_tax = __add_percent(selling_price, tax_rate);
    __write_number(tr_obj.find('input.unit_single_dsp_inc_tax'), selling_price_inc_tax);
});

//If purchase price inc tax is changed
$(document).on('change', 'input.unit_single_dp_inc_tax', function (e) {
    var tr_obj = $(this).closest('tr');

    var purchase_inc_tax = __read_number($(this));
    purchase_inc_tax = purchase_inc_tax == undefined ? 0 : purchase_inc_tax;

    var tax_rate = $('select#tax').find(':selected').data('rate');
    tax_rate = tax_rate == undefined ? 0 : tax_rate;

    var purchase_exc_tax = __get_principle(purchase_inc_tax, tax_rate);
    __write_number(tr_obj.find('input.unit_single_dpp'), purchase_exc_tax);

    var profit_percent = __read_number(tr_obj.find('input.profit_unit_percent'));
    var selling_price = __add_percent(purchase_exc_tax, profit_percent);
    __write_number(tr_obj.find('input.unit_single_dsp'), selling_price);

    var selling_price_inc_tax = __add_percent(selling_price, tax_rate);
    __write_number(tr_obj.find('input.unit_single_dsp_inc_tax'), selling_price_inc_tax);
});

$(document).on('change', 'input.profit_unit_percent', function (e) {
    var tax_rate = $('select#tax').find(':selected').data('rate');
    tax_rate = tax_rate == undefined ? 0 : tax_rate;

    var tr_obj = $(this).closest('tr');
    var profit_percent = __read_number($(this));

    var purchase_exc_tax = __read_number(tr_obj.find('input.unit_single_dpp'));
    purchase_exc_tax = purchase_exc_tax == undefined ? 0 : purchase_exc_tax;

    var selling_price = __add_percent(purchase_exc_tax, profit_percent);
    __write_number(tr_obj.find('input.unit_single_dsp'), selling_price);

    var selling_price_inc_tax = __add_percent(selling_price, tax_rate);
    __write_number(tr_obj.find('input.unit_single_dsp_inc_tax'), selling_price_inc_tax);
});

$(document).on('change', 'input.unit_single_dsp', function (e) {
    var tax_rate = $('select#tax').find(':selected').data('rate');
    tax_rate = tax_rate == undefined ? 0 : tax_rate;

    var tr_obj = $(this).closest('tr');
    var selling_price = __read_number($(this));
    var purchase_exc_tax = __read_number(tr_obj.find('input.unit_single_dpp'));

    var profit_percent = __get_rate(purchase_exc_tax, selling_price);
    __write_number(tr_obj.find('input.profit_unit_percent'), profit_percent);

    var selling_price_inc_tax = __add_percent(selling_price, tax_rate);
    __write_number(tr_obj.find('input.unit_single_dsp_inc_tax'), selling_price_inc_tax);
});
$(document).on('change', 'input.unit_single_dsp_inc_tax', function (e) {
    var tr_obj = $(this).closest('tr');
    var selling_price_inc_tax = __read_number($(this));

    var tax_rate = $('select#tax').find(':selected').data('rate');
    tax_rate = tax_rate == undefined ? 0 : tax_rate;

    var selling_price = __get_principle(selling_price_inc_tax, tax_rate);
    __write_number(tr_obj.find('input.unit_single_dsp'), selling_price);

    var purchase_exc_tax = __read_number(tr_obj.find('input.unit_single_dpp'));
    var profit_percent = __get_rate(purchase_exc_tax, selling_price);
    __write_number(tr_obj.find('input.profit_unit_percent'), profit_percent);
});
$(document).on('click', '.remove_unit_value_row', function () {
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var count = $(this).closest('table').find('.remove_unit_value_row').length;
            $(this).closest('tr').remove();
        }
    });
});
$.validator.addMethod(
    'uniqueSkuValues',
    function (value, element) {
        var $form = $(element).closest('form');
        var values = $form
            .find('.unique_sku')
            .map(function () {
                return this.value;
            })
            .get();

        var uniqueValues = [...new Set(values)]; // Remove duplicates

        return uniqueValues.length === values.length;
    },
    LANG.sku_must_unique
);
$.validator.addMethod(
    'uniqueUnitValues',
    function (value, element) {
        var $form = $(element).closest('form');
        var values = $form
            .find('.unique_unit')
            .map(function () {
                return this.value;
            })
            .get();

        var uniqueValues = [...new Set(values)]; // Remove duplicates

        return uniqueValues.length === values.length;
    },
    LANG.unit_must_unique
);


$.validator.addClassRules({
    unique_sku: {
        required: true,
        uniqueSkuValues: true,
    },
    unique_unit: {
        uniqueUnitValues: true,
    },

    unit_qty: {
        required: true,
    },
    unit_single_dpp: {
        required: true,
    },
    unit_single_dp_inc_tax: {
        required: true,
    },
    unit_single_dsp: {
        required: true,
    },
    single_dsp_inc_tax: {
        required: true,
    },
});

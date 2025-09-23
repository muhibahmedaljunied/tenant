$(document).ready(function () {
    getproducts();

    $(document).on(
        'change',
        `#product_list_filter_image,
        #product_list_filter_current_stock,
        #product_list_filter_type, 
        #product_list_filter_category_id, 
        #product_list_filter_brand_id, 
        #product_list_filter_unit_id, 
        #product_list_filter_tax_id,
        #location_id, 
                #store_id, 

        #active_state, 
        #repair_model_id`,
        function () {
            console.log('wowowo_no', 'getproductsgetproductscluser');

            $('#offset').val(0);
            $('#morebtn').html('المزيد');
            $('#rem').val('true');
            var products = document.getElementById('products');
            products.innerHTML = '';
            getproducts();
        }
    );
});

$(document).on('keyup', '#productname', function (e) {
    if (e.keyCode == 32 || $('#productname').val().trim() == '') return;
    $('#offset').val(0);
    $('#morebtn').html('المزيد');
    $('#rem').val('true');
    var products = document.getElementById('products');
    products.innerHTML = '';
    getproducts();
});
let isFiltering = false;

function getproducts() {
    // ------------------------muhib add this for prevent repeat of product when change location because store change in the same time --------------
    if (isFiltering) {
        console.log('Already filtering. Skipping...');
        return;
    }
    isFiltering = true;

    // --------------------------------------------------

    var offset = $('#offset').val() * 1;
    var rem = $('#rem').val();
    if (rem === 'false') {
        $('#morebtn').html('finshed');

        // -------------------muhib add this -------
        isFiltering = false;
        // --------------------------------------------------
        return;
    }

    $('#offset').val(offset + 12);
    $.ajax({
        url: '/gallery/gallery',
        type: 'GET',
        data: {
            type: $('#product_list_filter_type').val(),
            category_id: $('#product_list_filter_category_id').val(),
            brand_id: $('#product_list_filter_brand_id').val(),
            unit_id: $('#product_list_filter_unit_id').val(),
            tax_id: $('#product_list_filter_tax_id').val(),
            active_state: $('#active_state').val(),
            not_for_selling: $('#not_for_selling').is(':checked'),
            location_id: $('#location_id').val(),
            store_id: $('#store_id').val(),
            current_stock: $('#product_list_filter_current_stock').val(),
            image_type: $('#product_list_filter_image').val(),
            offset: offset,
            productname: $('#productname').val(),
        },
        success: function (data) {
            var products = document.getElementById('products');
            console.log('wowowo_yes', data);

            products.innerHTML += data['product'];
            if (data['count'] < 12) {
                $('#morebtn').html('finshed');
                $('#rem').val('false');
            }

            // ------------------muhib add rhis ----------------------------
            isFiltering = false;

            // --------------------------------------------------
        },
        // ------------------muhib add rhis ----------------------------

        error: function () {
            isFiltering = false;
        },
        // --------------------------------------------------
    });

    $('#loader').addClass('hidden');
}

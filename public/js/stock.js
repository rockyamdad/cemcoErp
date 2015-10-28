jQuery(document).ready(function() {
    // Put page-specific javascript here
    $('#stock_table').DataTable();
    var form = $('#stock_form');
    var error1 = $('.alert-danger', form);
    var success1 = $('.alert-success', form);

    form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules: {
            product_type: {
                required: true
            },
            product_id: {
                required: true
            },
            branch_id: {
                required: true
            },
            stock_info_id: {
                required: true
            },
            entry_type: {
                required: true
            },
            product_quantity: {
                required: true
            }
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
            success1.hide();
            error1.show();
            App.scrollTo(error1, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
        }

        /* submitHandler: function (form) {
         success1.show();
         error1.hide();
         }*/
    });

    $('#product_type').live("change", function () {
        var product_type = $('#product_type').val();
        $.ajax({
            type: "get",
            url: "products/"+product_type,
            success: function (html) {
                $('#product_id').html(html);

            }
        });
    });
    $('#edit_product_type').live("change", function () {
        var product_type = $('#edit_product_type').val();
        $.ajax({
            type: "get",
            url: "../products/"+product_type,
            success: function (html) {
                $('#edit_product_id').html(html);

            }
        });
    });

    $('#entry_type').live("change", function () {
        var entry_type = $('#entry_type').val();
        if(entry_type=='StockIn')
        {
            $('.to_stock_section').hide();
            $('.consignment_name_section').show();
            $.ajax({
                type: "get",
                url: "imports/",
                success: function (html) {
                   /* $('.consignment_name_section').html(html);*/
                    $('#consignment_name').html(html);

                }
            });

        }
        if(entry_type=='Transfer')
        {
            $('.to_stock_section').show();
            $('.consignment_name_section').hide();
            $.ajax({
                type: "get",
                url: "stocks/infos/",
                success: function (html) {
                    $('#to_stock_info_id').html(html);

                }
            });

        }
        if(entry_type=='Wastage')
        {
            $('.to_stock_section').hide();
            $('.consignment_name_section').hide();
        }
        if(entry_type=='StockOut')
        {
            $('.to_stock_section').hide();
            $('.consignment_name_section').hide();
        }


    });
    $('#edit_entry_type').live("change", function () {
        var entry_type = $('#edit_entry_type').val();
        if(entry_type=='StockIn')
        {
            $('.to_stock_section').hide();
            $('.consignment_name_section').show();
            $.ajax({
                type: "get",
                url: "../imports/",
                success: function (html) {
                    $('#consignment_name').html(html);

                }
            });

        }
        if(entry_type=='Transfer')
        {
            $('.to_stock_section').show();
            $('.consignment_name_section').hide();
            $.ajax({
                type: "get",
                url: "../stocks/infos/",
                success: function (html) {
                    $('#to_stock_section').html(html);

                }
            });

        }
        if(entry_type=='Wastage')
        {
            $('.to_stock_section').hide();
            $('.consignment_name_section').hide();
        }
        if(entry_type=='StockOut')
        {
            $('.to_stock_section').hide();
            $('.consignment_name_section').hide();
        }

    });

    $('select').select2();
    $('.consignment_name_section').hide();
    $('.to_stock_section').hide();
})
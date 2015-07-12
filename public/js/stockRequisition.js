jQuery(document).ready(function() {
    // Put page-specific javascript here

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
        if(entry_type==1)
        {
            $.ajax({
                type: "get",
                url: "imports/",
                success: function (html) {
                    $('.import_num_section').html(html);

                }
            });

        }else{
            $('.import_num_section').html("");
        }

    });
    $('#edit_entry_type').live("change", function () {
        var entry_type = $('#edit_entry_type').val();
        if(entry_type==1)
        {
            $.ajax({
                type: "get",
                url: "../imports/",
                success: function (html) {
                    $('.edit_import_num_section').html(html);

                }
            });

        }else{
            $('.edit_import_num_section').html("");
        }

    });

    $('select').select2();
})
jQuery(document).ready(function() {
    var form = $('#sales_return_form');
    var error1 = $('.alert-danger', form);
    var success1 = $('.alert-success', form);

    form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules: {
            party_id: {
                required: true
            },
            cus_ref_no: {
                required: true
            },
            branch_id: {
                required: true
            },
            product_type: {
                required: true
            },
            product_id: {
                required: true
            },
            quantity: {
                required: true
            },
            return_amount: {
                required: true
            },
            consignment_name: {
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

    $('select').select2();

    $('#product_type').live("change", function () {
        var product_type = $('#product_type').val();
        var branch = $('#products_branch_id').val();

        $('#product_id').empty();
        var newOption = $('<option value="">Select Product</option>');
        $('#product_id').append(newOption);
        $.ajax({
            type: "get",
            url: "product/"+product_type,
            data:{'data':branch},
            success: function (html) {

                $('#product_id').append(html);


            }
        });
    });

});
jQuery(document).ready(function() {
    // Put page-specific javascript here
    $('#branch_table').DataTable();
    var form = $('#branch_form');
    var error1 = $('.alert-danger', form);
    var success1 = $('.alert-success', form);

    form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules: {
            name: {
                required: true
            },
            location: {
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

    $('[data-toggle="modal"]').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var invoice = $(this).attr('rel');
        $.get(url, function(data) {
            $(data).modal();
            $('#invoice_id').val(invoice);
        });
    });
    $('select').select2();

    $('#account_category_id').live("change", function () {
        var account_category = $('#account_category_id').val();

        $.ajax({
            type: "get",
            url: "categories/"+account_category,
            success: function (html) {
                $('#account_name_id').html(html);

            }
        });
    });

    $('#category').live("change", function () {
        $('#invoice_id').val(Math.floor(Math.random()*9999999999));
    });
    $('.deleteExpenseTransaction').live("click", function() {
alert("ss");
        var transactionId = $(this).attr('rel');
        var parent = $(this).closest('tr');
        var answer     = confirm("Are you sure you want to delete this Expense Transaction?");
        if(answer) {
            $.ajax({
                type: "Get",
                url: "/deleteTransaction/"+transactionId,
                dateType: 'json',
                success: function (data) {
                    parent.remove();
                }
            });
        }
    });
});
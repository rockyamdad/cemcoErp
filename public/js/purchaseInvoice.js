jQuery(document).ready(function() {

    $(".savePurchaseInvoice").live("click", function () {
        if(purchaseFormValidation()){
            $.ajax({
                type: "POST",
                url: "/savePurchases",
                data :  $('#purchase_form').serialize(),
                dataType:'json',
                success:function(purchase)
                {
                    $("#product_id").val('');
                    $("#price").val('');
                    $("#quantity").val('');
                    $("#remarks").val('');

                    var html = [];
                    html.push('<td>' + purchase.product_id + '</td>');
                    html.push('<td>' + purchase.price + '</td>');
                    html.push('<td>' + purchase.quantity + '</td>');
                    if( purchase.remarks == ''){
                        html.push('<td>' + "Not Available" + '</td>');
                    }else{
                        html.push('<td>' + purchase.remarks + '</td>');
                    }
                    html.push('<td><input type="button"  id="deletePurchase" style="width:127px;" value="delete" class="btn red deletePurchase" rel=' + purchase.id + ' ></td>');

                    html = '<tr>' + html.join('') + '<tr>';
                    $('#purchaseTable  > tbody:first').append(html);
                }
            });
        } else {
            alert('You forgot to fill something out');
        }
    });
    $(".editPurchaseInvoice").live("click", function () {
        if(purchaseFormValidation()){
            var purchaseId = $(this).attr('rel');
            $.ajax({
                type: "POST",
                url: "/updatePurchases/"+purchaseId,
                data :  $('#purchase_form').serialize(),
                dataType:'json',
                success:function(purchase)
                {
                    $("#product_id").val('');
                    $("#price").val('');
                    $("#quantity").val('');
                    $("#remarks").val('');

                    var html = [];
                    html.push('<td>' + purchase.product_id + '</td>');
                    html.push('<td>' + purchase.price + '</td>');
                    html.push('<td>' + purchase.quantity + '</td>');
                    if( purchase.remarks == ''){
                        html.push('<td>' + "Not Available" + '</td>');
                    }else{
                        html.push('<td>' + purchase.remarks + '</td>');
                    }
                    html.push('<td><input type="button"  style="width:127px;" value="delete" class="btn red deletePurchase" rel=' + purchase.id + ' ></td>');

                    html = '<tr>' + html.join('') + '<tr>';
                    $('#purchaseTable  > tbody:first').append(html);
                }
            });
        } else {
            alert('You forgot to fill something out');
        }
    });
    function purchaseFormValidation() {

        var party = $.trim($('#party_id').val());
        var product = $.trim($('#product_id').val());
        var quantity = $.trim($('#quantity').val());
        var price = $.trim($('#price').val());

        if ((party === '') || (product === '') || (quantity === '' || price==='')) {
            return false;
        } else {
            return true;
        }
    }
    $('.deletePurchase').live("click", function() {

        var purchaseId = $(this).attr('rel');
        var parent = $(this).closest('tr');
        var answer     = confirm("Are you sure you want to delete this Purchase Invoice?");
        if(answer) {
            $.ajax({
                type: "Get",
                url: "/delete/"+purchaseId,
                dateType: 'json',
                success: function (data) {
                    parent.remove();
                }
            });
        }
    });
    $('.deletePurchaseDetail').live("click", function() {

        var purchaseDetailId = $(this).attr('rel');
        var parent = $(this).closest('tr');
        var answer     = confirm("Are you sure you want to delete this Purchase Invoice Detail?");
        if(answer) {
            $.ajax({
                type: "Get",
                url: "/deleteDetail/"+purchaseDetailId,
                dateType: 'json',
                success: function (data) {
                    parent.remove();
                }
            });
        }
    });
    $('#party_id').live("change", function () {
       $('#detail_invoice_id').val(Math.floor(Math.random()*9999999999));
        $("#party_id").attr('readonly','readonly');
    });


    $(".party_id").attr('readonly','readonly');



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


     /*$('#savePayment').live("click", function () {

        var form = $('.payment_form');
        var error1 = $('.alert-danger', form);
        var success1 = $('.alert-success', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                amount: {
                    required: true
                },
                account_category_id: {
                    required: true
                },
                account_name_id: {
                    required: true
                },
                payment_method: {
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
            },

             submitHandler: function (form) {

             error1.hide();
             }
        });
    });*/
    $('.deletePurchaseTransaction').live("click", function() {

        var transactionId = $(this).attr('rel');
        var parent = $(this).closest('tr');
        var answer     = confirm("Are you sure you want to delete this Purchase Invoice Detail?");
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
jQuery(document).ready(function() {
    $('#Purchasetable').DataTable({
        "bPaginate": false
    });
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


    $('.deletePurchaseTransaction').live("click", function() {

        var transactionId = $(this).attr('rel');
        var parent = $(this).closest('tr');
        var answer     = confirm("Are you sure you want to delete this Purchase Transaction?");
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
jQuery(document).ready(function() {
    $('#salestable').DataTable({
        "bPaginate": false
    });
    $(".saveSales").live("click", function () {
        if(saleFormValidation()){
            $.ajax({
                type: "POST",
                url: "/saveSale",
                data :  $('#sale_form').serialize(),
                dataType:'json',
                success:function(sale)
                {
                    $("#product_id").val('');
                    $("#price").val('');
                    $("#quantity").val('');
                    $("#remarks").val('');

                    var html = [];
                    html.push('<td>' + sale.product_id + '</td>');
                    html.push('<td>' + sale.price + '</td>');
                    html.push('<td>' + sale.quantity + '</td>');
                    if( sale.remarks == ''){
                        html.push('<td>' + "Not Available" + '</td>');
                    }else{
                        html.push('<td>' + sale.remarks + '</td>');
                    }
                    html.push('<td><input type="button"   style="width:127px;" value="delete" class="btn red deleteSaleDetail" rel=' + sale.id + ' ></td>');

                    html = '<tr>' + html.join('') + '<tr>';
                    $('#saleTable  > tbody:first').append(html);
                }
            });
        } else {
            alert('You forgot to fill something out');
        }
    });
    $(".editSale").live("click", function () {
        if(saleFormValidation()){
            var saleId = $(this).attr('rel');
            $.ajax({
                type: "POST",
                url: "/updatePurchases/"+saleId,
                data :  $('#sale_form').serialize(),
                dataType:'json',
                success:function(sale)
                {
                    $("#product_id").val('');
                    $("#price").val('');
                    $("#quantity").val('');
                    $("#remarks").val('');

                    var html = [];
                    html.push('<td>' + sale.product_id + '</td>');
                    html.push('<td>' + sale.price + '</td>');
                    html.push('<td>' + sale.quantity + '</td>');
                    if( sale.remarks == ''){
                        html.push('<td>' + "Not Available" + '</td>');
                    }else{
                        html.push('<td>' + sale.remarks + '</td>');
                    }
                    html.push('<td><input type="button"  style="width:127px;" value="delete" class="btn red deleteSaleDetail" rel=' + sale.id + ' ></td>');

                    html = '<tr>' + html.join('') + '<tr>';
                    $('#saleTable  > tbody:first').append(html);
                }
            });
        } else {
            alert('You forgot to fill something out');
        }
    });
    function saleFormValidation() {

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
    $('select').select2();
    $('.deleteSale').live("click", function() {

        var saleDetailId = $(this).attr('rel');
        var parent = $(this).closest('tr');
        var answer     = confirm("Are you sure you want to delete this Sales Detail?");
        if(answer) {
            $.ajax({
                type: "Get",
                url: "/deleteDetail/"+saleDetailId,
                dateType: 'json',
                success: function (data) {
                    parent.remove();
                }
            });
        }
    });
    $('.deleteSaleDetail').live("click", function() {

        var saleDetailId = $(this).attr('rel');
        var parent = $(this).closest('tr');
        var answer     = confirm("Are you sure you want to delete this Sales Detail?");
        if(answer) {
            $.ajax({
                type: "Get",
                url: "/deleteDetail/"+saleDetailId,
                dateType: 'json',
                success: function (data) {
                    parent.remove();
                }
            });
        }
    });
    $('#party_id').live("change", function () {
       $('#invoice_id').val(Math.floor(Math.random()*9999999999));
        $("#party_id").attr('readonly','readonly');
    });


    $(".party_id").attr('readonly','readonly');

    $('.deleteSaleTransaction').live("click", function() {

        var transactionId = $(this).attr('rel');
        var parent = $(this).closest('tr');
        var answer     = confirm("Are you sure you want to delete this Sale Transaction?");
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
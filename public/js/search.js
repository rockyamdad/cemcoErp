jQuery(document).ready(function() {
    // Put page-specific javascript here
   // $('#stock_Product_search_result_table').DataTable();
   // $('#stock_search_result_table').DataTable();
   // $('#stock_requisition_search_result_table').DataTable();
    $('.date-picker').datepicker();

    $('select').select2();

    $('#category_id').live("change", function () {
        $('#product_id').empty();
        var category_id = $('#category_id').val();
        $.ajax({
            type: "get",
            url: "products/"+category_id,
            success: function (html) {
                $('#product_id').append(html);

            }
        });
    });

});
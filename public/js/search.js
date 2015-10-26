jQuery(document).ready(function() {
    // Put page-specific javascript here
    $('#stock_Product_search_result_table').DataTable();
    $('#stock_search_result_table').DataTable();
    $('#stock_requisition_search_result_table').DataTable();
    $('.date-picker').datepicker();

    $('select').select2();

});
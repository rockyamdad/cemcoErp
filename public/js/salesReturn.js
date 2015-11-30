jQuery(document).ready(function() {
// Put page-specific javascript here

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
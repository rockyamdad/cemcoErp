jQuery(document).ready(function() {
    // Put page-specific javascript here

    $('.date-picker').datepicker()

    $('select').select2();


    $('[data-toggle="modal"]').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $(data).modal();

        });
    });


});
$('select').select2();
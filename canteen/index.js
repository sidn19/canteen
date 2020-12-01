jQuery(function() {
    jQuery('#showall').click(function() {
        jQuery('.targetDiv').hide();
    });

    $(document).ready(function() {
        $('#landingsite').click();
    });

    jQuery('.showSingle').click(function() {
        jQuery('.targetDiv').hide();
        jQuery('#div' + $(this).attr('target')).show();

    });

    $('li').on('click', function() {
        $('li').removeClass('current');
        $(this).addClass('current');
    });


    // search box jquery



});
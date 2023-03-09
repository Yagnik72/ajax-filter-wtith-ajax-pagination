jQuery(document).ready(function () {
    var page;
    // Search by branch name
    jQuery('#adt_search').keyup(function () {
        page = 1;
        adt_branch_fn();
    });

    // Location radio click
    jQuery('input[name="adt_location"]').change(function () {
        page = 1;
        adt_branch_fn();
    });

    // Groups radio click
    jQuery('input[name="adt_groups"]').change(function () {
        page = 1;
        adt_branch_fn();
    });

    // Pagination search
    jQuery('.fidelity-services-group').on('click', '#gt_pagination a', function (e) {
        e.preventDefault();
        page = jQuery(this).html();
        if (page.indexOf('Previous') != -1) {
            page = jQuery('#gt_pagination span.current').html();
            page = parseInt(page) - 1;
        } else if (page.indexOf('Next') != -1) {
            page = jQuery('#gt_pagination span.current').html();
            page = parseInt(page) + 1;
        }
        adt_branch_fn();
    });

    // Common AJAX function
    function adt_branch_fn() {
        // Get input values
        var adt_search = jQuery('input[name="adt_search"]').val();
        var adt_location = jQuery('input[name="adt_location"]:checked').val();
        var adt_groups = jQuery('input[name="adt_groups"]:checked').val();

        // Pause for 500 milliseconds
        setTimeout(function () {
            // Fire the AJAX
            jQuery.ajax({
                url: branch_ajax.ajaxurl,
                type: "POST",
                async: false,
                data: {
                    action: 'branch_filter',
                    page_number: page,
                    adt_search: adt_search,
                    adt_location: adt_location,
                    adt_groups: adt_groups
                },
                success: function (response) {
                    if (response != '') {
                        jQuery('.fidelity-services-group').html('');
                        jQuery('.fidelity-services-group').html(response);
                    }
                }
            });
        }, 300);
    }
});
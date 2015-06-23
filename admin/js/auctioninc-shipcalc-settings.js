jQuery(document).ready(function($) {

    toggle_method_fields();

    $(document).on('change', '#wpec_auctioninc_calc_method', function(e) {
        toggle_method_fields();
    });

    $(document).on('change', '#wpec_auctioninc_fixed_mode', function(e) {
        toggle_fixed_fields();
    });

    function toggle_method_fields() {
        var calc_method = $('#wpec_auctioninc_calc_method').val();
        if (calc_method === 'C' || calc_method === 'CI') {
            $('#wpec_auctioninc_package').closest('tr').fadeIn();
            $('#wpec_auctioninc_insurance').closest('tr').fadeIn();
            $('#wpec_auctioninc_fixed_mode').closest('tr').fadeOut();
            $('#wpec_auctioninc_fixed_code').closest('tr').fadeOut();
            $('#wpec_auctioninc_fixed_fee_1').closest('tr').fadeOut();
            $('#wpec_auctioninc_fixed_fee_2').closest('tr').fadeOut();
        }
        else if (calc_method === 'F') {
            $('#wpec_auctioninc_package').closest('tr').fadeOut();
            $('#wpec_auctioninc_insurance').closest('tr').fadeOut();
            $('#wpec_auctioninc_fixed_mode').closest('tr').fadeIn();
            toggle_fixed_fields();
        }
        else {
            $('#wpec_auctioninc_package').closest('tr').hide();
            $('#wpec_auctioninc_insurance').closest('tr').hide();
            $('#wpec_auctioninc_fixed_mode').closest('tr').hide();
            $('#wpec_auctioninc_fixed_code').closest('tr').hide();
            $('#wpec_auctioninc_fixed_fee_1').closest('tr').hide();
            $('#wpec_auctioninc_fixed_fee_2').closest('tr').hide();
        }
    }

    function toggle_fixed_fields() {
        var fixed_mode = $('#wpec_auctioninc_fixed_mode').val();
        if (fixed_mode === 'code') {
            $('#wpec_auctioninc_fixed_code').closest('tr').fadeIn();
            $('#wpec_auctioninc_fixed_fee_1').closest('tr').fadeOut();
            $('#wpec_auctioninc_fixed_fee_2').closest('tr').fadeOut();
        }
        else if (fixed_mode === 'fee') {
            $('#wpec_auctioninc_fixed_code').closest('tr').fadeOut();
            $('#wpec_auctioninc_fixed_fee_1').closest('tr').fadeIn();
            $('#wpec_auctioninc_fixed_fee_2').closest('tr').fadeIn();
        }
        else {
            $('#wpec_auctioninc_fixed_code').closest('tr').hide();
            $('#wpec_auctioninc_fixed_fee_1').closest('tr').hide();
            $('#wpec_auctioninc_fixed_fee_2').closest('tr').hide();
        }
    }

});
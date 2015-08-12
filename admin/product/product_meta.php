<?php

/**
 * Adds a box to the main column on the Product edit screens.
 */
function wpec_auctioninc_add_meta_box() {

    $screens = array('wpsc-product');

    foreach ($screens as $screen) {
        add_meta_box('wpec_auctioninc_shipping', __('AuctionInc ShipCalc Settings', 'wpec_auctioninc'), 'wpec_auctioninc_meta_box_callback', $screen, 'normal', 'default');
    }
}

add_action('add_meta_boxes', 'wpec_auctioninc_add_meta_box');

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current product.
 */
function wpec_auctioninc_meta_box_callback($post) {

    // Default values
    $auctioninc_settings = get_option('wpec_auctioninc');

    $calc_method = get_post_meta($post->ID, 'auctioninc_calc_method', true);
    $calc_method = !empty($calc_method) ? $calc_method : $auctioninc_settings['calc_method'];

    $package = get_post_meta($post->ID, 'auctioninc_pack_method', true);
    $package = !empty($package) ? $package : $auctioninc_settings['package'];
    $package = !empty($package) ? $package : 'T';

    $insurable = get_post_meta($post->ID, 'auctioninc_insurable', true);
    $insurable = !empty($insurable) ? $insurable : $auctioninc_settings['insurance'];

    $fixed_mode = get_post_meta($post->ID, 'auctioninc_fixed_mode', true);
    $fixed_mode = !empty($fixed_mode) ? $fixed_mode : $auctioninc_settings['fixed_mode'];

    $fixed_code = get_post_meta($post->ID, 'auctioninc_fixed_code', true);
    $fixed_code = !empty($fixed_code) ? $fixed_code : $auctioninc_settings['fixed_code'];

    $fixed_fee_1 = get_post_meta($post->ID, 'auctioninc_fixed_fee_1', true);
    $fixed_fee_1 = is_numeric($fixed_fee_1) ? $fixed_fee_1 : $auctioninc_settings['fixed_fee_1'];

    $fixed_fee_2 = get_post_meta($post->ID, 'auctioninc_fixed_fee_2', true);
    $fixed_fee_2 = is_numeric($fixed_fee_2) ? $fixed_fee_2 : $auctioninc_settings['fixed_fee_2'];

    echo '<a href="http://www.auctioninc.com/info/page/auctioninc_shipping_settings" target="_blank">' . __('AuctionInc Help', 'wpec_auctioninc') . '</a>';

    // Add an nonce field so we can check for it later.
    wp_nonce_field('wpec_auctioninc_meta_box', 'wpec_auctioninc_meta_box_nonce');

    echo '<table class="form-table meta_box">';

    // Calculation Methods
    $calc_methods = array(
        '' => __('-- Select -- ', 'wpec_auctioninc'),
        'C' => __('Carrier Rates', 'wpec_auctioninc'),
        'F' => __('Fixed Fee', 'wpec_auctioninc'),
        'N' => __('Free', 'wpec_auctioninc'),
        'CI' => __('Free Domestic', 'wpec_auctioninc')
    );

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_calc_method">' . __('Calculation Method', 'auctioninc') . '</label';
    echo '</th>';
    echo '<td>';
    echo '<select name="auctioninc_calc_method" id="auctioninc_calc_method">';

    foreach ($calc_methods as $k => $v) {
        $selected = $calc_method == $k ? 'selected' : '';
        echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
    }

    echo '</select>';
    echo '<p class="description">' . __('Select base calculation method. Please consult the AuctionInc Help Guide for more information.', 'auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Fixed Mode
    $fixed_modes = array(
        '' => __('-- Select -- ', 'wpec_auctioninc'),
        'code' => __('Code', 'wpec_auctioninc'),
        'fee' => __('Fee', 'wpec_auctioninc')
    );

    echo '<tr>';
    echo '<th>';
    echo '<label for="">' . __('Fixed Mode', 'auctioninc') . '</label';
    echo '</th>';
    echo '<td>';
    echo '<select name="auctioninc_fixed_mode" id="auctioninc_fixed_mode">';

    foreach ($fixed_modes as $k => $v) {
        $selected = $fixed_mode == $k ? 'selected' : '';
        echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
    }

    echo '</select>';
    echo '</td>';
    echo '</tr>';

    // Fixed Fee Code
    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_fixed_code">' . __('Fixed Fee Code', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<input type="text" name="auctioninc_fixed_code" id="auctioninc_fixed_code" value="' . esc_attr($fixed_code) . '">';
    echo '<p class="description">' . __('Enter your AuctionInc-configured fixed fee code.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Fixed Fee 1
    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_fixed_fee_1">' . __('Fixed Fee 1', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<input type="text" name="auctioninc_fixed_fee_1" id="auctioninc_fixed_fee_1" value="' . esc_attr($fixed_fee_1) . '" placeholder="0.00">';
    echo '<p class="description">' . __('Enter fee for first item.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Fixed Fee 2
    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_fixed_fee_2">' . __('Fixed Fee 2', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<input type="text" name="auctioninc_fixed_fee_2" id="auctioninc_fixed_fee_2" value="' . esc_attr($fixed_fee_2) . '" placeholder="0.00">';
    echo '<p class="description">' . __('Enter fee for additional items and quantities.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Package
    $pack_methods = array(
        '' => __('-- Select -- ', 'wpec_auctioninc'),
        'T' => __('Together', 'wpec_auctioninc'),
        'S' => __('Separately', 'wpec_auctioninc')
    );

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_pack_method">' . __('Package', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<select name="auctioninc_pack_method" id="auctioninc_pack_method">';

    foreach ($pack_methods as $k => $v) {
        $selected = $package == $k ? 'selected' : '';
        echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
    }

    echo '</select>';
    echo '<p class="description">' . __('Select "Together" for items that can be packed in the same box with other items from the same origin.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Insurable
    $checked = $insurable == 'yes' ? 'checked' : '';

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_package">' . __('Insurable', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<input type="checkbox" name="auctioninc_insurable" id="auctioninc_insurable" value="yes" ' . $checked . '>';
    echo __('Enable Insurance');
    echo '<p class="description">' . __('Include product value for insurance calculation based on AuctionInc settings.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Origin Code
    $origin_code = get_post_meta(get_the_ID(), 'auctioninc_origin_code', true);

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_origin_code">' . __('Origin Code', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<input type="text" name="auctioninc_origin_code" id="auctioninc_origin_code" value="' . esc_attr($origin_code) . '">';
    echo '<p class="description">' . __('If item is not shipped from your default AuctionInc location, enter your AuctionInc origin code here.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Supplemental Item Handling Mode
    $supp_handling_mode = get_post_meta(get_the_ID(), 'auctioninc_supp_handling_mode', true);

    $supp_handling_modes = array(
        '' => __('-- Select -- ', 'wpec_auctioninc'),
        'code' => __('Code', 'wpec_auctioninc'),
        'fee' => __('Fee', 'wpec_auctioninc')
    );

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_supp_handling_mode">' . __('Supplemental Item Handling Mode', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<select name="auctioninc_supp_handling_mode" id="auctioninc_supp_handling_mode">';

    foreach ($supp_handling_modes as $k => $v) {
        $selected = $supp_handling_mode == $k ? 'selected' : '';
        echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
    }

    echo '</select>';
    echo '<p class="description">' . __('Supplements your AuctionInc-configured package and order handling for this item.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Supplemental Handling Code
    $supp_handling_code = get_post_meta(get_the_ID(), 'auctioninc_supp_handling_code', true);

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_supp_handling_code">' . __('Supplemental Item Handling Code', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<input type="text" name="auctioninc_supp_handling_code" id="auctioninc_supp_handling_code" value="' . esc_attr($supp_handling_code) . '">';
    echo '<p class="description">' . __('Enter your AuctionInc-configured Supplemental Handling Code.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // Supplemental Item Handling Fee
    $supp_handling_fee = get_post_meta(get_the_ID(), 'auctioninc_supp_handling_fee', true);

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_supp_handling_fee">' . __('Supplemental Item Handling Fee', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<input type="text" name="auctioninc_supp_handling_fee" id="auctioninc_supp_handling_fee" value="' . esc_attr($supp_handling_fee) . '" placeholder="0.00">';
    echo '</td>';
    echo '</tr>';

    // On-Demand Service Codes
    $selected_ondemand_codes = get_post_meta(get_the_ID(), 'auctioninc_ondemand_codes', true);

    $ondemand_codes = array(
    'DHLWPE' => __('DHL Worldwide Priority Express', 'wpec_auctioninc'),
    'DHL9AM' => __('DHL Express 9 A.M.', 'wpec_auctioninc'),
    'DHL10AM' => __('DHL Express 10:30 A.M.', 'wpec_auctioninc'),
    'DHL12PM' => __('DHL Express 12 P.M.', 'wpec_auctioninc'),
    'DHLES' => __('DHL Domestic Economy Select', 'wpec_auctioninc'),
    'DHLEXA' => __('DHL Domestic Express 9 A.M.', 'wpec_auctioninc'),
    'DHLEXM' => __('DHL Domestic Express 10:30 A.M.', 'wpec_auctioninc'),
    'DHLEXP' => __('DHL Domestic Express 12 P.M.', 'wpec_auctioninc'),
    'DHLDE' => __('DHL Domestic Express 6 P.M.', 'wpec_auctioninc'),
    'FDX2D' => __('FedEx 2 Day', 'wpec_auctioninc'),
    'FDX2DAM' => __('FedEx 2 Day AM', 'wpec_auctioninc'),
    'FDXES' => __('FedEx Express Saver', 'wpec_auctioninc'),
    'FDXFO' => __('FedEx First Overnight', 'wpec_auctioninc'),
    'FDXPO' => __('FedEx Priority Overnight', 'wpec_auctioninc'),
    'FDXPOS' => __('FedEx Priority Overnight Saturday Delivery', 'wpec_auctioninc'),
    'FDXSO' => __('FedEx Standard Overnight', 'wpec_auctioninc'),
    'FDXGND' => __('FedEx Ground', 'wpec_auctioninc'),
    'FDXHD' => __('FedEx Home Delivery', 'wpec_auctioninc'),
    'FDXIGND' => __('FedEx International Ground', 'wpec_auctioninc'),
    'FDXIE' => __('FedEx International Economy', 'wpec_auctioninc'),
    'FDXIF' => __('FedEx International First', 'wpec_auctioninc'),
    'FDXIP' => __('FedEx International Priority', 'wpec_auctioninc'),
    'UPSNDA' => __('UPS Next Day Air', 'wpec_auctioninc'),
    'UPSNDE' => __('UPS Next Day Air Early AM', 'wpec_auctioninc'),
    'UPSNDAS' => __('UPS Next Day Air Saturday Delivery', 'wpec_auctioninc'),
    'UPSNDS' => __('UPS Next Day Air Saver', 'wpec_auctioninc'),
    'UPS2DE' => __('UPS 2 Day Air AM', 'wpec_auctioninc'),
    'UPS2ND' => __('UPS 2nd Day Air', 'wpec_auctioninc'),
    'UPS3DS' => __('UPS 3 Day Select', 'wpec_auctioninc'),
    'UPSGND' => __('UPS Ground', 'wpec_auctioninc'),
    'UPSCAN' => __('UPS Standard', 'wpec_auctioninc'),
    'UPSWEX' => __('UPS Worldwide Express', 'wpec_auctioninc'),
    'UPSWSV' => __('UPS Worldwide Saver', 'wpec_auctioninc'),
    'UPSWEP' => __('UPS Worldwide Expedited', 'wpec_auctioninc'),
    'USPFC' => __('USPS First-Class Mail', 'wpec_auctioninc'),
    'USPEXP' => __('USPS Priority Express', 'wpec_auctioninc'),
    'USPLIB' => __('USPS Library', 'wpec_auctioninc'),
    'USPMM' => __('USPS Media Mail', 'wpec_auctioninc'),
    'USPPM' => __('USPS Priority', 'wpec_auctioninc'),
    'USPPP' => __('USPS Standard Post', 'wpec_auctioninc'),
    'USPFCI' => __('USPS First Class International', 'wpec_auctioninc'),
    'USPPMI' => __('USPS Priority Mail International', 'wpec_auctioninc'),
    'USPEMI' => __('USPS Priority Express Mail International', 'wpec_auctioninc'),
    'USPGXG' => __('USPS Global Express Guaranteed', 'wpec_auctioninc')
    );

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_ondemand_codes">' . __('On-Demand Service Codes', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<select name="auctioninc_ondemand_codes[]" id="auctioninc_ondemand_codes" multiple>';

    foreach ($ondemand_codes as $k => $v) {
        $selected = in_array($k, $selected_ondemand_codes) ? 'selected' : '';
        echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
    }

    echo '</select>';
    echo '<p class="description">' . __('Select any AuctionInc configured on-demand services for which this item is eligible. Hold [Ctrl] key for multiple selections.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    // On-Demand Service Codes
    $selected_access_fees = get_post_meta(get_the_ID(), 'auctioninc_access_fees', true);

    $access_fees = array(
        'AddlHandling' => __('Additional Handling Charge, All Carriers', 'wpec_auctioninc'),
        'AddlHandlingUPS' => __('Additional Handling Charge, UPS', 'wpec_auctioninc'),
        'AddlHandlingDHL' => __('Additional Handling Charge, DHL', 'wpec_auctioninc'),
        'AddlHandlingFDX' => __('Additional Handling Charge, FedEx', 'wpec_auctioninc'),
        'Hazard' => __('Hazardous Charge, All Carriers', 'wpec_auctioninc'),
        'HazardUPS' => __('Hazardous Charge, UPS', 'wpec_auctioninc'),
        'HazardDHL' => __('Hazardous Charge, DHL', 'wpec_auctioninc'),
        'HazardFDX' => __('Hazardous Charge, FedEx', 'wpec_auctioninc'),
        'SignatureReq' => __('Signature Required Charge, All Carriers', 'wpec_auctioninc'),
        'SignatureReqUPS' => __('Signature Required Charge, UPS', 'wpec_auctioninc'),
        'SignatureReqDHL' => __('Signature Required Charge, DHL', 'wpec_auctioninc'),
        'SignatureReqFDX' => __('(Indirect) Signature Required  Charge, FedEx', 'wpec_auctioninc'),
        'SignatureReqUSP' => __('Signature Required Charge, USPS', 'wpec_auctioninc'),
        'UPSAdultSignature' => __('Adult Signature Required Charge, UPS', 'wpec_auctioninc'),
        'DHLAdultSignature' => __('Adult Signature Required Charge, DHL', 'wpec_auctioninc'),
        'FDXAdultSignature' => __('Adult Signature Required Charge, FedEx', 'wpec_auctioninc'),
        'DHLPrefSignature' => __('Signature Preferred Charge, DHL', 'wpec_auctioninc'),
        'FDXDirectSignature' => __('(Direct) Signature Required  Charge, FedEx', 'wpec_auctioninc'),
        'FDXHomeCertain' => __('Home Date Certain Charge, FedEx Home Delivery', 'wpec_auctioninc'),
        'FDXHomeEvening' => __('Home Date Evening Charge, FedEx Home Delivery', 'wpec_auctioninc'),
        'FDXHomeAppmnt' => __('Home Appmt. Delivery Charge, FedEx Home Delivery', 'wpec_auctioninc'),
        'Pod' => __('Proof of Delivery Charge, All Carriers', 'wpec_auctioninc'),
        'PodUPS' => __('Proof of Delivery Charge, UPS', 'wpec_auctioninc'),
        'PodDHL' => __('Proof of Delivery Charge, DHL', 'wpec_auctioninc'),
        'PodFDX' => __('Proof of Delivery Charge, FedEx', 'wpec_auctioninc'),
        'PodUSP' => __('Proof of Delivery Charge, USPS', 'wpec_auctioninc'),
        'UPSDelivery' => __('Delivery Confirmation Charge, UPS', 'wpec_auctioninc'),
        'USPCertified' => __('Certified Delivery Charge, USPS', 'wpec_auctioninc'),
        'USPRestricted' => __('Restricted Delivery Charge, USPS', 'wpec_auctioninc'),
        'USPDelivery' => __('Delivery Confirmation Charge, USPS', 'wpec_auctioninc'),
        'USPReturn' => __('Return Receipt Charge, USPS', 'wpec_auctioninc'),
        'USPReturnMerchandise' => __('Return Receipt for Merchandise Charge, USPS', 'wpec_auctioninc'),
        'USPRegistered' => __('Registered Mail Charge, USPS', 'wpec_auctioninc'),
        'IrregularUSP' => __('Irregular Package Discount,USPS', 'wpec_auctioninc')
    );

    echo '<tr>';
    echo '<th>';
    echo '<label for="auctioninc_access_fees">' . __('Special Accessorial Fees', 'wpec_auctioninc') . '</label>';
    echo '</th>';
    echo '<td>';
    echo '<select name="auctioninc_access_fees[]" id="auctioninc_access_fees" multiple>';

    foreach ($access_fees as $k => $v) {
        $selected = in_array($k, $selected_access_fees) ? 'selected' : '';
        echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
    }

    echo '</select>';
    echo '<p class="description">' . __('Add preferred special carrier fees. Hold [Ctrl] key for multiple selections.', 'wpec_auctioninc') . '</p>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function wpec_auctioninc_save_meta_box_data($post_id) {
    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['wpec_auctioninc_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['wpec_auctioninc_meta_box_nonce'], 'wpec_auctioninc_meta_box')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'wpsc-product' == $_POST['post_type']) {

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $calc_method = sanitize_text_field($_POST['auctioninc_calc_method']);
        update_post_meta($post_id, 'auctioninc_calc_method', $calc_method);

        $fixed_mode = sanitize_text_field($_POST['auctioninc_fixed_mode']);
        update_post_meta($post_id, 'auctioninc_fixed_mode', $fixed_mode);

        $fixed_fee_1 = floatval($_POST['auctioninc_fixed_fee_1']);
        update_post_meta($post_id, 'auctioninc_fixed_fee_1', $fixed_fee_1);

        $fixed_fee_2 = floatval($_POST['auctioninc_fixed_fee_2']);
        update_post_meta($post_id, 'auctioninc_fixed_fee_2', $fixed_fee_2);

        $package = sanitize_text_field($_POST['auctioninc_pack_method']);
        update_post_meta($post_id, 'auctioninc_pack_method', $package);

        $insurable = sanitize_text_field($_POST['auctioninc_insurable']);
        update_post_meta($post_id, 'auctioninc_insurable', $insurable);

        $origin_code = sanitize_text_field($_POST['auctioninc_origin_code']);
        update_post_meta($post_id, 'auctioninc_origin_code', $origin_code);

        $supp_handling_mode = sanitize_text_field($_POST['auctioninc_supp_handling_mode']);
        update_post_meta($post_id, 'auctioninc_supp_handling_mode', $supp_handling_mode);

        $supp_handling_code = sanitize_text_field($_POST['auctioninc_supp_handling_code']);
        update_post_meta($post_id, 'auctioninc_supp_handling_code', $supp_handling_code);

        $supp_handling_fee = sanitize_text_field($_POST['auctioninc_supp_handling_fee']);
        update_post_meta($post_id, 'auctioninc_supp_handling_fee', $supp_handling_fee);

        $ondemand_codes_dirty = $_POST['auctioninc_ondemand_codes'];
        $ondemand_codes = is_array($ondemand_codes_dirty) ? array_map('sanitize_text_field', $ondemand_codes_dirty) : sanitize_text_field($ondemand_codes_dirty);
        update_post_meta($post_id, 'auctioninc_ondemand_codes', $ondemand_codes);

        $access_fees_dirty = $_POST['auctioninc_access_fees'];
        $access_fees = is_array($access_fees_dirty) ? array_map('sanitize_text_field', $access_fees_dirty) : sanitize_text_field($access_fees_dirty);
        update_post_meta($post_id, 'auctioninc_access_fees', $access_fees);
    }
}

add_action('save_post', 'wpec_auctioninc_save_meta_box_data');

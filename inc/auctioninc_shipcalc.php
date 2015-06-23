<?php
class auctioninc_shipcalc {

    var $internal_name;
    var $name;
    var $is_external;
    var $singular_shipping;

    function __construct() {

        $this->internal_name = "auctioninc_shipcalc";
        $this->name = "AuctionInc ShipCalc";
        $this->is_external = true;
        $this->needs_zipcode = true;
        $this->singular_shipping = false;

        return true;
    }

    function getName() {
        return $this->name;
    }

    function getInternalName() {
        return $this->internal_name;
    }

    function getForm() {

        $auctioninc_settings = get_option('wpec_auctioninc');

        // API Settings
        $output .= '<tr>';
        $output .= '<td colspan="2">';
        $output .= '<strong>' . __('API Settings', 'wpec_auctioninc') . '</strong>';
        $output .= '<p class="description">' . __('An AuctionInc account ID is required to enable shipping rates.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        // AuctionInc Account ID
        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('AuctionInc Account ID', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<input type="text" name="wpec_auctioninc[id]" id="wpec_auctioninc_account_id" value="' . esc_attr($auctioninc_settings['id']) . '">';
        $output .= '<p class="description">' . __('Please enter your account ID that you received when you registered at the AuctionInc site.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        // Delivery Type
        $delivery_types = array(
            'residential' => __('Residential', 'wpec_auctioninc'),
            'commercial' => __('Commercial', 'wpec_auctioninc')
        );

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Delivery Destination Type', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<select class="select " name="wpec_auctioninc[delivery_type]" id="wpec_auctioninc_delivery_type">';

        foreach ($delivery_types as $k => $v) {
            $selected = $auctioninc_settings['delivery_type'] == $k ? 'selected' : '';
            $output .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }

        $output .= '</select>';
        $output .= '<p class="description">' . __('Set rates to apply to either residential or commercial destination addresses.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        // Default Values
        $output .= '<tr>';
        $output .= '<td colspan="2">';
        $output .= '<strong>' . __('Default Values', 'wpec_auctioninc') . '</strong>';
        $output .= '<p class="description">' . __('These settings will apply to those products for which you haven\'t configured AuctionInc values.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        // Calculation Method
        $calc_methods = array(
            '' => __('-- Select -- ', 'wpec_auctioninc'),
            'C' => __('Carrier Rates', 'wpec_auctioninc'),
            'F' => __('Fixed Fee', 'wpec_auctioninc'),
            'N' => __('Free', 'wpec_auctioninc'),
            'CI' => __('Free Domestic', 'wpec_auctioninc')
        );

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Calculation Method', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<select name="wpec_auctioninc[calc_method]" id="wpec_auctioninc_calc_method">';

        foreach ($calc_methods as $k => $v) {
            $selected = $auctioninc_settings['calc_method'] == $k ? 'selected' : '';
            $output .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }

        $output .= '</select>';
        $output .= '<p class="description">' . __('For carrier rates, your configured product weights & dimensions will be used.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        // Package
        $pack_methods = array(
            '' => __('-- Select -- ', 'wpec_auctioninc'),
            'T' => __('Together', 'wpec_auctioninc'),
            'S' => __('Separately', 'wpec_auctioninc')
        );

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Package Items', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<select name="wpec_auctioninc[package]" id="wpec_auctioninc_package">';

        foreach ($pack_methods as $k => $v) {
            $selected = $auctioninc_settings['package'] == $k ? 'selected' : '';
            $output .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }

        $output .= '</select>';
        $output .= '<p class="description">' . __('Select to pack items from the same origin into the same box or each in its own box.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        // Insurance
        $checked = $auctioninc_settings['insurance'] == 'yes' ? 'checked' : '';

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Insurance', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<input type="checkbox" name="wpec_auctioninc[insurance]" id="wpec_auctioninc_insurance" value="yes" ' . $checked . '>';
        $output .= __('Enable Insurance');
        $output .= '<p class="description">' . __('If enabled your items will utilize your AuctionInc insurance settings.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        // Fixed Mode
        $fixed_modes = array(
            '' => __('-- Select -- ', 'wpec_auctioninc'),
            'code' => __('Code', 'wpec_auctioninc'),
            'fee' => __('Fee', 'wpec_auctioninc')
        );

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Mode', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<select name="wpec_auctioninc[fixed_mode]" id="wpec_auctioninc_fixed_mode">';

        foreach ($fixed_modes as $k => $v) {
            $selected = $auctioninc_settings['fixed_mode'] == $k ? 'selected' : '';
            $output .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }

        $output .= '</select>';
        $output .= '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Code', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<input type="text" name="wpec_auctioninc[fixed_code]" id="wpec_auctioninc_fixed_code" value="' . esc_attr($auctioninc_settings['fixed_code']) . '">';
        $output .= '<p class="description">' . __('Enter your AuctionInc-configured fixed fee code.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Fixed Fee 1', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<input type="text" name="wpec_auctioninc[fixed_fee_1]" id="wpec_auctioninc_fixed_fee_1" value="' . esc_attr($auctioninc_settings['fixed_fee_1']) . '" placeholder="0.00">';
        $output .= '<p class="description">' . __('Enter fee for first item.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Fixed Fee 2', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<input type="text" name="wpec_auctioninc[fixed_fee_2]" id="wpec_auctioninc_fixed_fee_2" value="' . esc_attr($auctioninc_settings['fixed_fee_2']) . '" placeholder="0.00">';
        $output .= '<p class="description">' . __('Enter fee for additional items and quantities.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td colspan="2">';
        $output .= '<strong>' . __('Fallback Rate', 'wpec_auctioninc') . '</strong>';
        $output .= '<p class="description">' . __('Default rate if the API cannot be reached or if no rates are found.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        $fallback_types = array(
            '' => __('-- Select -- ', 'wpec_auctioninc'),
            'per_item' => __('Per Item', 'wpec_auctioninc'),
            'per_order' => __('Per Order', 'wpec_auctioninc')
        );

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Type', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<select name="wpec_auctioninc[fallback_type]" id="wpec_auctioninc_fallback_type">';

        foreach ($fallback_types as $k => $v) {
            $selected = $auctioninc_settings['fallback_type'] == $k ? 'selected' : '';
            $output .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }

        $output .= '</select>';
        $output .= '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Amount', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<input type="text" name="wpec_auctioninc[fallback_fee]" id="wpec_auctioninc_fallback_fee" value="' . esc_attr($auctioninc_settings['fallback_fee']) . '" placeholder="0.00">';
        $output .= '<p class="description">' . __('Enter an amount for the fallback rate.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';

        /*$checked = $auctioninc_settings['debug_mode'] == 1 ? 'checked' : '';
        $output .= '<tr>';
        $output .= '<td>';
        $output .= __('Debug Mode', 'wpec_auctioninc');
        $output .= '</td>';
        $output .= '<td>';
        $output .= '<input type="checkbox" name="wpec_auctioninc[debug_mode]" id="wpec_auctioninc_debug_mode" value="1" ' . $checked . '>';
        $output .= __('Enable debug mode');
        $output .= '<p class="description">' . __('Enable debug mode to show debugging data for ship rates in your cart. Only you, not your customers, can view this debug data.', 'wpec_auctioninc') . '</p>';
        $output .= '</td>';
        $output .= '</tr>';*/

        return $output;
    }

    function submit_form() {
        if ($_POST['wpec_auctioninc'] != null) {

            $shipping = (array) get_option('wpec_auctioninc');
            $submitted_shipping = (array) $_POST['wpec_auctioninc'];

            $submitted_shipping['id'] = sanitize_text_field($submitted_shipping['id']);
            $submitted_shipping['delivery_type'] = sanitize_text_field($submitted_shipping['delivery_type']);
            $submitted_shipping['package'] = sanitize_text_field($submitted_shipping['package']);
            $submitted_shipping['insurance'] = sanitize_text_field($submitted_shipping['insurance']);
            $submitted_shipping['fixed_mode'] = sanitize_text_field($submitted_shipping['fixed_mode']);
            $submitted_shipping['fixed_code'] = sanitize_text_field($submitted_shipping['fixed_code']);
            $submitted_shipping['fixed_fee_1'] = floatval($submitted_shipping['fixed_fee_1']);
            $submitted_shipping['fixed_fee_2'] = floatval($submitted_shipping['fixed_fee_2']);
            $submitted_shipping['fallback_type'] = sanitize_text_field($submitted_shipping['fallback_type']);
            $submitted_shipping['fallback_fee'] = floatval($submitted_shipping['fallback_fee']);
            //$submitted_shipping['debug_mode'] = intval($submitted_shipping['debug_mode']);

            update_option('wpec_auctioninc', array_merge($shipping, $submitted_shipping));
        }

        return true;
    }

    /* If there is a per-item shipping charge that applies irrespective of the chosen shipping method
     * then it should be calculated and returned here. The value returned from this function is used
     * as-is on the product pages. It is also included in the final cart & checkout figure along
     * with the results from GetQuote (below) */

    function get_item_shipping() {
        
    }

    /* This function returns an Array of possible shipping choices, and associated costs.
     * This is for the cart in general, per item charges (As returned from get_item_shipping (above))
     * will be added on as well. */

    function getQuote() {
        
        global $wpdb, $wpsc_cart;

        if (isset($_POST['country']) && isset($_POST['zipcode'])) {
            $country = $_POST['country'];
            $zipcode = $_POST['zipcode'];
        } else {
            $country = wpsc_get_customer_meta('shippingcountry');
            $zipcode = (string) wpsc_get_customer_meta('shippingpostcode');
        }

        // AuctionInc Settings
        $auctioninc_settings = get_option('wpec_auctioninc');

        $currency_type = get_option('currency_type');
        $currency_country = new WPSC_Country($currency_type);

        $base_currency = $currency_country->get_currency_code();   // Country currency code

        global $current_user;
        $is_admin = (!empty($current_user->roles) && in_array('administrator', $current_user->roles)) ? true : false;

        if (!empty($wpsc_cart) && !empty($auctioninc_settings['id'])) {
            if (!empty($country) && !empty($zipcode)) {
                $rates = array();

                // Instantiate the Shipping Rate API object
                $shipAPI = new ShipRateAPI($auctioninc_settings['id']);

                // SSL currently not supported
                $shipAPI->setSecureComm(false);

                // Header reference code
                $shipAPI->setHeaderRefCode('wpec');

                // Set base currency
                $shipAPI->setCurrency($base_currency);

                // Set the Detail Level (1, 2 or 3) (Default = 1)
                // DL 1:  minimum required data returned 
                // DL 2:  shipping rate components included
                // DL 3:  package-level detail included
                $detailLevel = 3;
                $shipAPI->setDetailLevel($detailLevel);

                // Show table of any errors for inspection
                $showErrors = true;

                // Set Destination Address for this API call
                $destCountryCode = $country;
                $destPostalCode = $zipcode;
                $destStateCode = '';

                // Specify residential delivery
                $delivery_type = $auctioninc_settings['delivery_type'] == 'residential' ? true : false;

                $shipAPI->setDestinationAddress($destCountryCode, $destPostalCode, $destStateCode, $delivery_type);

                // Create an array of items to rate
                $items = array();

                // Loop through package items
                foreach ($wpsc_cart->cart_items as $cartitem) {
                    // Skip digital items
                    if ($cartitem->meta[0]['no_shipping'] == 1) {
                        continue;
                    }

                    // Get AuctionInc shipping fields
                    $product_id = $cartitem->product_id;
                    $sku = $cartitem->sku;

                    // Calculation Method
                    $calc_method = get_post_meta($product_id, 'auctioninc_calc_method', true);
                    $calc_method = !empty($calc_method) ? $calc_method : $auctioninc_settings['calc_method'];

                    // Fixed Fee Mode
                    $fixed_mode = get_post_meta($product_id, 'auctioninc_fixed_mode', true);
                    $fixed_mode = !empty($fixed_mode) ? $fixed_mode : $auctioninc_settings['fixed_mode'];

                    // Fixed Fee Code
                    $fixed_code = get_post_meta($product_id, 'auctioninc_fixed_code', true);
                    $fixed_code = !empty($fixed_code) ? $fixed_code : $auctioninc_settings['fixed_code'];

                    // Fixed Fee 1
                    $fixed_fee_1 = get_post_meta($product_id, 'auctioninc_fixed_fee_1', true);
                    $fixed_fee_1 = is_numeric($fixed_fee_1) ? $fixed_fee_1 : $auctioninc_settings['fixed_fee_1'];

                    // Fixed Fee 2
                    $fixed_fee_2 = get_post_meta($product_id, 'auctioninc_fixed_fee_2', true);
                    $fixed_fee_2 = is_numeric($fixed_fee_2) ? $fixed_fee_2 : $auctioninc_settings['fixed_fee_2'];

                    // Packaging Method
                    $pack_method = get_post_meta($product_id, 'auctioninc_pack_method', true);
                    $pack_method = !empty($pack_method) ? $pack_method : $auctioninc_settings['package'];

                    // Insurable
                    $insurable = get_post_meta($product_id, 'auctioninc_insurable', true);
                    $insurable = !empty($insurable) ? $insurable : $auctioninc_settings['insurance'];

                    $_insurable = get_post_meta($product_id, 'auctioninc_insurable', true);

                    // Origin Code
                    $origin_code = get_post_meta($product_id, 'auctioninc_origin_code', true);

                    // Supplemental Item Handling Mode
                    $supp_handling_mode = get_post_meta($product_id, 'auctioninc_supp_handling_mode', true);

                    // Supplemental Item Handling Code
                    $supp_handling_code = get_post_meta($product_id, 'auctioninc_supp_handling_code', true);

                    // Supplemental Item Handling Fee
                    $supp_handling_fee = get_post_meta($product_id, 'auctioninc_supp_handling_fee', true);

                    // On-Demand Service Codes
                    $ondemand_codes = get_post_meta($product_id, 'auctioninc_ondemand_codes', true);

                    // Special Accessorial Fees
                    $access_fees = get_post_meta($product_id, 'auctioninc_access_fees', true);

                    $item = array();

                    $item_ref_code = $cartitem->product_name;
                    if (!empty($sku)) {
                        $item_ref_code .= "- $sku";
                    }

                    $item["refCode"] = $item_ref_code;
                    $item["CalcMethod"] = $calc_method;
                    $item["quantity"] = $cartitem->quantity;

                    if ($calc_method === 'C' || $calc_method === 'CI') {
                        $item["packMethod"] = $pack_method;
                    }

                    // Fixed Rate Shipping
                    if ($calc_method === 'F') {

                        if (!empty($fixed_mode)) {
                            if ($fixed_mode === 'code' && !empty($fixed_code)) {
                                $item["FeeType"] = "C";
                                $item["fixedFeeCode"] = $fixed_code;
                            } elseif ($fixed_mode === 'fee' && is_numeric($fixed_fee_1) && (is_numeric($fixed_fee_2) || empty($fixed_fee_2)) ) {
                                $item["FeeType"] = "F";
                                $item["fixedAmt_1"] = $fixed_fee_1;
                                if(empty($fixed_fee_2)) $fixed_fee_2 = 0;
                                $item["fixedAmt_2"] = $fixed_fee_2;
                            }
                        }
                    }

                    // Insurable
                    if ($insurable == 'yes') {
                        $item["value"] = $cartitem->total_price;
                    } else {
                        $item["value"] = 0;
                    }

                    // Origin Code
                    if (!empty($origin_code)) {
                        $item["originCode"] = $origin_code;
                    }

                    if ($calc_method === 'C' || $calc_method === 'CI') {
                        // Weight
                        $wpec_weight_unit = $cartitem->meta[0]['weight_unit'];
                        $weight = $cartitem->meta[0]['weight'];

                        $item["weight"] = $weight;
                        $item["weightUOM"] = "LBS";

                        // Dimensions
                        if ($cartitem->meta[0]['dimensions']) {
                            if ($cartitem->meta[0]['dimension_unit'] == 'meter') {
                                $item["length"] = $cartitem->meta[0]['dimensions']['length'] * 39.3701;
                                $item["height"] = $cartitem->meta[0]['dimensions']['height'] * 39.3701;
                                $item["width"] = $cartitem->meta[0]['dimensions']['width'] * 39.3701;
                                $item["dimUOM"] = 'IN';
                            } else {
                                $item["length"] = $cartitem->meta[0]['dimensions']['length'];
                                $item["height"] = $cartitem->meta[0]['dimensions']['height'];
                                $item["width"] = $cartitem->meta[0]['dimensions']['width'];
                                $item["dimUOM"] = $cartitem->meta[0]['dimension_unit'];
                            }
                        }
                    }

                    // Supplemental Item Handling
                    if (!empty($supp_handling_mode)) {
                        if ($supp_handling_mode === 'code' && !empty($supp_handling_code)) {
                            // Supplemental Item Handling Code
                            $item["suppHandlingCode"] = $supp_handling_code;
                        } elseif ($supp_handling_mode === 'fee' && !empty($supp_handling_fee)) {
                            // Supplemental Item Handling Fee
                            $item["suppHandlingFee"] = $supp_handling_fee;
                        }
                    }

                    // On-Demand Service Codes
                    if (!empty($ondemand_codes)) {
                        $codes_str = implode(", ", $ondemand_codes);
                        $item["odServices"] = $codes_str;
                    }

                    // Special Accessorial Fees
                    if (!empty($access_fees)) {
                        $codes_str = implode(", ", $access_fees);
                        $item["specCarrierSvcs"] = $codes_str;
                    }

                    // Add this item to Item Array
                    $items[] = $item;
                }

                // Debug output
                if (($auctioninc_settings['debug_mode'] == 1) && ($is_admin === true)) {
                    echo 'DEBUG ITEM DATA<br>';
                    echo '<pre>' . print_r($items, true) . '</pre>';
                    echo 'END DEBUG ITEM DATA<br>';
                }

                // Add Item Data from Item Array to API Object
                foreach ($items AS $val) {
                    if ($val["CalcMethod"] == "C" || $val["CalcMethod"] == "CI") {

                        $shipAPI->addItemCalc($val["refCode"], $val["quantity"], $val["weight"], $val['weightUOM'], $val["length"], $val["width"], $val["height"], $val["dimUOM"], $val["value"], $val["packMethod"], 1, $val["CalcMethod"]);
                        if (isset($val["originCode"]))
                            $shipAPI->addItemOriginCode($val["originCode"]);
                        if (isset($val["odServices"]))
                            $shipAPI->addItemOnDemandServices($val["odServices"]);
                        if (isset($val["suppHandlingCode"]))
                            $shipAPI->addItemSuppHandlingCode($val["suppHandlingCode"]);
                        if (isset($val["suppHandlingFee"]))
                            $shipAPI->addItemHandlingFee($val["suppHandlingFee"]);
                        if (isset($val["specCarrierSvcs"]))
                            $shipAPI->addItemSpecialCarrierServices($val["specCarrierSvcs"]);
                    } elseif ($val["CalcMethod"] == "F") {
                        $shipAPI->addItemFixed($val["refCode"], $val["quantity"], $val["FeeType"], $val["fixedAmt_1"], $val["fixedAmt_2"], $val["fixedFeeCode"]);
                    } elseif ($val["CalcMethod"] == "N") {
                        $shipAPI->addItemFree($val["refCode"], $val["quantity"]);
                    }
                }

                // Unique identifier for cart items & destiniation
                $request_identifier = serialize($items) . $destCountryCode . $destPostalCode;

                // Check for cached response
                $transient = 'ai_quote_' . md5($request_identifier);
                $cached_response = get_transient($transient);

                $shipRates = array();

                if ($cached_response !== false) {
                    //Cached response
                    $shipRates = unserialize($cached_response);
                } else {
                    // New API call
                    $ok = $shipAPI->GetItemShipRateSS($shipRates);
                    //if ($ok) {
                    set_transient($transient, serialize($shipRates), 30 * MINUTE_IN_SECONDS);
                    //}
                }

                if (!empty($shipRates['ShipRate'])) {

                    // Store response in the current user's session
                    // Used to retrieve package level details later
                    $_SESSION['auctioninc_response'] = $shipRates;

                    // Debug output
                    if (($auctioninc_settings['debug_mode'] == 1) && ($is_admin === true)) {
                        echo 'DEBUG API RESPONSE: SHIP RATES<br>';
                        echo '<pre>' . print_r($shipRates, true) . '</pre>';
                        echo 'END DEBUG API RESPONSE: SHIP RATES<br>';
                    }

                    foreach ($shipRates['ShipRate'] as $shipRate) {
                        // Add Rate
                        $rates[$shipRate['ServiceName']] = (float) $shipRate['Rate'];
                    }
                } else {

                    if (($auctioninc_settings['debug_mode'] == 1) && ($is_admin === true)) {
                        echo 'DEBUG API RESPONSE: SHIP RATES<br>';
                        echo '<pre>' . print_r($shipRates, true) . '</pre>';
                        echo 'END DEBUG API RESPONSE: SHIP RATES<br>';
                    }

                    $use_fallback = false;

                    if (empty($shipRates['ErrorList'])) {
                        $use_fallback = true;
                    } else {
                        foreach ($shipRates['ErrorList'] as $error) {
                            // Check for proper error code
                            if ($error['Message'] == 'Packaging Engine unable to determine any services to be rated') {
                                $use_fallback = true;
                                break;
                            }
                        }
                    }

                    // Add fallback shipping rates, if applicable
                    $fallback_type = $auctioninc_settings['fallback_type'];
                    $fallback_fee = $auctioninc_settings['fallback_fee'];
                    if (!empty($fallback_type) && !empty($fallback_fee) && $use_fallback == true) {

                        // Total cart quanitity
                        $total_quantity = 0;
                        foreach ($wpsc_cart->cart_items as $cartitem) {
                            $total_quantity = $total_quantity + $cartitem->quantity;
                        }

                        $cost = $fallback_type === 'per_order' ? $fallback_fee : $total_quantity * $fallback_fee;

                        $rates['Shipping'] = (float) $cost;
                    } else {
                        //$str = __('There do not seem to be any available shipping rates. Please double check your address, or contact us if you need any help.', 'wc_auctioninc');
                        //$this->display_notice($str, 'error');
                    }
                }

                // Return rates
                return $rates;
            }
        } else {
            return array();
        }
    }

}
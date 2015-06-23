<?php

/**
 * Prints the box content.
 * 
 * @param WPSC_Purchase_Log $log_id The object for the current purchase log.
 */
function wpec_auctioninc_display_purchase_meta($log_id) {
    echo '<div class="metabox-holder">';
    echo '<div id="purchlogs_notes" class="postbox">';
    echo '<h3 class="hndle">' . __('AuctionInc Packaging Details', 'wpec_auctioninc') . '</h3>';
    echo '<div class="inside">';

    $shipping_meta = wpsc_get_purchase_meta($log_id, 'auctioninc_order_shipping_meta', true);

    if (!empty($shipping_meta['PackageDetail'])) {
        
        $i = 1;
        foreach ($shipping_meta['PackageDetail'] as $package) :
            $flat_rate_code = !empty($package['FlatRateCode']) ? $package['FlatRateCode'] : __('NONE', 'wpec_auctioninc');
            ?>
            <strong><?php echo __('Package', 'wpec_auctioninc') . "# $i"; ?></strong><br>
            <?php
            echo __('Flat Rate Code', 'wpec_auctioninc') . ": $flat_rate_code<br>";
            echo __('Quantity', 'wpec_auctioninc') . ": {$package['Quantity']}<br>";
            echo __('Pack Method', 'wpec_auctioninc') . ": {$package['PackMethod']}<br>";
            echo __('Origin', 'wpec_auctioninc') . ": {$package['Origin']}<br>";
            echo __('Declared Value', 'wpec_auctioninc') . ": {$package['DeclaredValue']}<br>";
            echo __('Weight', 'wpec_auctioninc') . ": {$package['Weight']}<br>";
            echo __('Length', 'wpec_auctioninc') . ": {$package['Length']}<br>";
            echo __('Width', 'wpec_auctioninc') . ": {$package['Width']}<br>";
            echo __('Height', 'wpec_auctioninc') . ": {$package['Height']}<br>";
            echo __('Oversize Code', 'wpec_auctioninc') . ": {$package['OversizeCode']}<br>";
            echo __('Carrier Rate', 'wpec_auctioninc') . ": ".number_format($package['CarrierRate'],2)."<br>";
            echo __('Fixed Rate', 'wpec_auctioninc') . ": ".number_format($package['FixedRate'],2)."<br>";
            echo __('Surcharge', 'wpec_auctioninc') . ": ".number_format($package['Surcharge'],2)."<br>";
            echo __('Fuel Surcharge', 'wpec_auctioninc') . ": ".number_format($package['FuelSurcharge'],2)."<br>";
            echo __('Insurance', 'wpec_auctioninc') . ": ".number_format($package['Insurance'],2)."<br>";
            echo __('Handling', 'wpec_auctioninc') . ": ".number_format($package['Handling'],2)."<br>";
            echo __('Total Rate', 'wpec_auctioninc') . ": ".number_format($package['ShipRate'],2)."<br>";

            $j = 1;
            echo '<br>';
            foreach ($package['PkgItem'] as $pkg_item) :
                ?>
                <strong><?php echo __('Item', 'auctioninc') . "# $j"; ?></strong><br>
                <?php
                echo __('Ref Code', 'wpec_auctioninc') . ": {$pkg_item['RefCode']}<br>";
                echo __('Quantity', 'wpec_auctioninc') . ": {$pkg_item['Qty']}<br>";
                echo __('Weight', 'wpec_auctioninc') . ": {$pkg_item['Weight']}<br>";
                $j++;
            endforeach;
            echo '<br><br>';
            $i++;
        endforeach;
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
}

add_action('wpsc_purchlogitem_metabox_end', 'wpec_auctioninc_display_purchase_meta');

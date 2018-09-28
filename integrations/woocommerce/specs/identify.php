<?php
namespace Segment\Integrations\WooCommerce;
use CreativeFuse\Woo\Checkout;

function get_identity_address_values_from_order( $identify ){

     // Are we on a WooCommerce Thank You Page?
     if( did_action( 'woocommerce_thankyou' ) ){

        // Get the order received endpoint in order to get the order ID
        $order_id = Checkout\get_order_id_from_query_string();
        $order = wc_get_order( $order_id );

        // If we don't have an order, give$identify back unmodified
        if( ! $order )
            return $identify;

        // If there is a second part to their address, use it, otherwise default to part 1
        $street = $order->get_billing_address_2()
                ? $order->get_billing_address_1() . ', ' . $order->get_billing_address_2()
                : $order->get_billing_address_1();

        // Add our address to the main identify call
        $identify['traits']['address'] = array(

            'street'        => $street,
            'city'          => $order->get_billing_city(),
            'state'         => $order->get_billing_state(),
            'postalCode'    => $order->get_billing_postcode(),
            'country'       => $order->get_billing_country()

        );

    }

    return $identify;

}
<?php
namespace Segment\Integrations\WooCommerce;
use CreativeFuse\Woo\Checkout;
use Segment\Core\Cookie;

/**
 * Adds product properties to analytics.track() when product added to cart.
 *
 * @since  1.0.0
 * @access public
 *
 * @return array Filtered array of name and properties for analytics.track().
 */
function track_product_added_to_cart( $track_events ) {

	// If there is a valid add to cart cookie set
	if ( Cookie\get_cookie( 'added_to_cart' ) != false ) {

		// Make sure we can get our cart
		if ( ! \WC()->cart )
			return $track_events;

		// Get info about the product that was just added to the cart
		$_product = json_decode( Cookie\get_cookie( 'added_to_cart' ) );

		// If we can decode the product and have info
		if ( $_product ) {

			// Let's get ALL the info about the product we just added
			$product  = wc_get_product( $_product->product_id );

			// If we have access to a real product
			if ( $product ) {

				$item = array(

					'product_id'    => $product->get_id(),
					'sku'      		=> $product->get_sku(),
					'name'     		=> $product->get_title(),
					'price'    		=> $product->get_price(),
					'quantity' 		=> $_product->quantity,
					'category' 		=> strtolower( implode( ', ', wp_list_pluck( wc_get_product_terms( $product->id, 'product_cat' ), 'name' ) ) ),
                    'variant'		=> $_product->variant,

				);

				// Remove all empty array items
				$item = array_filter( $item );

				$track = array(

					'event'      => 'Added Product',
					'properties' => $item,
					'http_event' => 'added_to_cart'

				);

			}
        }

        // Append this track to standard tracking events
        $track_events[] = $track;

	}

	return $track_events;

}


/**
 * Add Order Events to .track(), including all product data.
 *
 * @param array $track_events The array of Segment .track() events
 * @return array $track_events
 */
function track_order_completed( $track_events ) {

    // Bail if we didn't do our WooCommerce thank you action
    if( ! did_action('woocommerce_thankyou') )
        return $track_events;

	 // Get the order received endpoint in order to get the order ID
     $order_id = Checkout\get_order_id_from_query_string();
     $order = wc_get_order( $order_id );

    // Bail if no actual order object
    if( ! $order )
        return $track_events;

	/* Because gateways vary wildly in their usage of the status concept, we check for failure rather than success. */
	if ( $order->get_status() !== 'failed' ) {

        // Build initial variables
		$items        = $order->get_items();
        $products     = array();
        $dp = wc_get_price_decimals();

        // Loop over all items in the order
		foreach ( $items as $item ) {

            // Tap into our product data
            $product_data = $order->get_product_from_item( $item );

            // We always want to get the parent ID of our products, as variations can be tracked in other ways ( skus, variation list )
            $product_id = ($product_data->post_type === 'product_variation') && ( ! empty($item->get_variation_id() ) )
                            ? $product_data->get_parent_id()
                            : $product_data->get_id();

            // Build variations if we are dealing with a product with variants
            $variations = ($product_data->post_type === 'product_variation') && ( ! empty($item->get_variation_id() ) )
                        ? implode( ', ', $product_data->get_variation_attributes() )
                        : '';

            // Get any coupons that might have been applied to the order
            $coupons = $order->get_used_coupons()
                        ? implode( ', ', $order->get_used_coupons() )
                        : '';

            /**
             * Build our product on each iteration.
             * We are grabbing all the data we can in
             * order to send it to Segment Later
             */
			$product = array(

				'product_id'    => $product_id,
				'sku'           => $product_data->get_sku(),
				'name'          => $product_data->get_title(),
				'price'         => wc_format_decimal( $order->get_item_total($item, false, false), $dp ),
				'quantity'      => $item['qty'],
				'category'      => strtolower( implode( ', ', wp_list_pluck( wc_get_product_terms( $item['product_id'], 'product_cat' ), 'name' ) ) ),
				'variant'       => $variations

			);

            // Remove empty values from products array
            $product = array_filter( $product );

            // Add each product to the array of products on each loop
			$products[] = $product;

		}

        /**
         * We are finally building our track event
         * to sent to Segment
         */
		$track = array(
            'event'      => 'Order Completed',

			'properties' => array(

				'checkout_id'   => $order->get_transaction_id(),
				'order_id'      => $order->get_order_number(),
				'total'         => wc_format_decimal( $order->get_total(), $dp ),
				'revenue'       => wc_format_decimal( $order->get_total() - ( $order->get_total_shipping() + $order->get_total_tax() ), $dp ),
				'shipping'      => wc_format_decimal( $order->get_total_shipping(), $dp ),
				'tax'           => wc_format_decimal( $order->get_total_tax(), $dp ),
				'discount'      => wc_format_decimal( $order->get_total_discount(), $dp ),
                'currency'      => $order->get_currency(),
                'coupons'       => $coupons,
				'products'      => $products
			)
        );

        // Add our new event to the array of Segment events to fire
        $track_events[] = $track;

    }

	return $track_events;
}
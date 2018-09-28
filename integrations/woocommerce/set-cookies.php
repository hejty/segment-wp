<?php
namespace Segment\Integrations\WooCommerce;
use Segment\Core\Cookie;


/**
 * Adds product information to a Segment_Cookie when item is removed from cart.
 *
 * @param string $key      Key name for item in cart.  A hash.
 *
 * @since  1.0.0
 * @access public
 *
 * @uses  func_get_args() Because our abstract class doesn't know how many parameters are passed to each hook
 *                        for each different platform, we use func_get_args().
 */
function set_remove_from_cart_cookie( $key ) {

	if ( ! is_object( WC()->cart ) ) {
		return;
	}

	$items = WC()->cart->get_cart();

	$cart_item = $items[ $key ];
	if ( ! is_object( $cart_item ) ) {
		return;
	}

	Cookie\set_cookie( 'removed_from_cart', json_encode(

			array(
				'ID'       => $cart_item->product_id,
				'quantity' => 0,
				'name'     => $cart_item['data']->post->post_title,
				'price'    => $cart_item['data']->get_price(),
				'key'      => $key
			)
		)
	);
}


/**
 * Adds product information to a Segment_Cookie when item is added to cart.
 *
 * @param string $key      Key name for item in cart.  A hash.
 * @param int    $id       Product ID
 * @param int    $quantity Item quantity
 *
 * @since  1.0.0
 *
 */
function set_add_to_cart_cookie( $key, $id, $quantity ){

	if ( ! \WC()->cart )
        return;

	$product_data = \WC()->cart->get_cart_item( $key );

    if( ! $product_data )
        return;

    $product_variation = $product_data['variation']
                            ? implode( ', ', $product_data['variation'] )
                            : '';

    $product = [

        'product_id'    => $id,
        'quantity'	    => $quantity,
        'key'		    => $key,
        'variant'       => $product_variation

    ];

    Cookie\set_cookie( 'added_to_cart', json_encode( $product ) );

}
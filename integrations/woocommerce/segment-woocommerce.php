<?php
namespace Segment\Integrations\WooCommerce;

// Load our spec functions
include_once( __DIR__ . '/specs/identify.php' );
include_once( __DIR__ . '/specs/track.php' );
include_once( __DIR__ . '/set-cookies.php' );



// Filter the Identify Event
add_filter( 'segment_identify_event', __NAMESPACE__ . '\get_identity_address_values_from_order', 10, 1 );


// Filter the standard page tracking event and pass in appropriate data
add_filter( 'segment_track_events', __NAMESPACE__ . '\track_order_completed', 10, 1);
add_filter( 'segment_track_events', __NAMESPACE__ . '\track_product_added_to_cart', 10, 2);
//add_filter( 'segment_track_events', __NAMESPACE__ . '\track_product_removed_from_cart', 2);
//add_filter( 'segment_track_events', __NAMESPACE__ . '\track_cart_viewed', 1);
//add_filter( 'segment_track_events', __NAMESPACE__ . '\track_checkout_started', 1);
//add_filter( 'segment_track_events', __NAMESPACE__ . '\track_order_refunded', 2);

/**
 * HTTP actions are used to track AJAX actions such as
 * adding an item to the cart or removing an item from the cart
 */
add_action( 'woocommerce_add_to_cart', __NAMESPACE__ . '\set_add_to_cart_cookie', 10, 3 );
// add_action( 'woocommerce_before_cart_item_quantity_zero', __NAMESPACE__ . '\set_remove_from_cart_cookie', 10, 1 );
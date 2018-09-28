<?php
namespace Segment\Core\LoadAnalyticsJs;
use Segment\Helpers\Environment;
use Segment;

/**
 * Load the Segment analytics.js snippet into our site
 *
 * @return void
 */
add_action('wp_head', __NAMESPACE__ . '\load_segment', 1 );

function load_segment(){

    // Get the current logged-in user
    $user = wp_get_current_user();

    // Check to see if we are going to ignore segment
    $ignore =  maybe_ignore_segment_load( $user );

    $segment_api_key = get_option( 'segment_api_key' );

    // Bail if no Api Key exists
    if ( ! isset( $segment_api_key ) || $segment_api_key == '' )
        return;

    /**
     * Load the Segment Snippet  with before
     * and after action hooks
     */
    do_action( 'segment_before_snippet_load' );

        // Load the Segment snippet
        include( \Segment\module()->get_dir() . '/assets/spec-js-templates/analytics-js-snippet.php' );

    do_action( 'segment_after_snippet_load' );

}

/**
 * Check to see if we should ignore Segment loading
 * based on either our default config setting or
 * based on the current logged-in user level.
 *
 * @param object $user The current logged-in user
 *
 * @return bool $ignore Whether or not we should ignore Segment loading
 */
function maybe_ignore_segment_load( $user = '' ){

    // Get the current environment
    $environment = Environment\get_environment();

    // Get the default ignore preference from our settings
    $ignore = \Segment\module()->get_config('segment_ignore_load')[ $environment ];

    // Get default settings for user ignore check
    $ignore_above_user_level = \Segment\module()->get_config('segment_ignore_above_user_level')[ $environment ];

    /**
     * If the current user level is equal to or above the ignore threshold,
     * ignore segment load.
     */
    if( $user && $user->user_level >= $ignore_above_user_level ){
        $ignore = true;
    }

    // Allow $ignore to be modified from anywhere
    $ignore = apply_filters( 'segment_ignore_load', $ignore );

    return $ignore;

}
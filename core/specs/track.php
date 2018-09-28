<?php
namespace Segment\Core\Specs\Track;
use Segment\Core\JsSpecCall;

/**
 * This is where the magic happens for all .track() calls.
 *
 * Instead of defining the name and type of each track function and then using
 * JsSpecCall\track in each of the functions (resulting in a lot of extra code to maintain and harder integrations),
 * we are essentially setting up a function which, by default, does nothing.
 * It sets itself to be ready to receive track events from filter hooks. Once it is hooked into,
 * it loops over an array of track events and calls our .track() segment event for each of them.
 * This allows us to land on a page where multiple events take place and successfully track all of them.
 *
 * @since 1.0.0
 * @return void
 */
function init_track_events(){

    // By default, we don't have any track events
    $track_events = false;

    // Track Events will be added via this filter
    $track_events = apply_filters( 'segment_track_events' , array( $track_events ) );

    /**
     * Remove any empty array items (blank, null, false)
     *
     * Note that we must do this because our $track_events is set to
     * false by default, yet it still exists in our array after running
     * apply_filters(). Also, it's just good to do this anyway
     * to prevent any empty arrays from making their way through.
     */
     $track_events = array_filter($track_events );

    // Bail if we don't have any track events
    if( ! $track_events )
    return;

    /**
     * Get all of our track events
     */
    foreach( $track_events as $track ){

        // We don't have a page we want to track.
        if ( ! isset( $track ) ) {
            $track = false;
        }

        // Track exists
        if ( $track ){

            /**
             * Clean out empty properties before sending it back. to make sure we
             * never pass Segment an empty track event
             */
            $track['properties'] = array_filter( $track['properties'] );

            /**
             * If an HTTP Event is set, pass it through, otherwise, we need this to be false
             * to prevent our track AJAX from firing.
             */
            $http_event = isset( $track['http_event'] ) ? $track['http_event'] : false;

            // Finally pass our event into our call to Segment
            JsSpecCall\track( $track['event'], $track['properties'], array(), $http_event );

        }

    }


}
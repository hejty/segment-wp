<?php
namespace Segment\Core\JsSpecCall;

/**
 * Render a Javascript `track` call
 *
 * @since  1.0.0
 *
 * @param  string  $event       The name of the event to pass to Segment.
 * @param  array   $properties  An array of properties to pass to Segment.
 * @param  array   $options     An array of options to pass to Segment.
 * @param  boolean $http_event  Whether or not the event is occurring over HTTP, as opposed to on page load.
 *                              This is helpful to track events that occur between page loads, like commenting.
 *
 */
function track( $event_name, $properties = [], $options = [], $http_event = false ){

    include( \Segment\module()->get_dir() . '/assets/spec-js-templates/track.php' );

}

/**
 * Render a Javascript `identify` call
 *
 * @since  1.0.0
 *
 * @param  int|string  $user_id Current User ID.
 *                              Generated via get_current_user_id() if logged in, anonymous user ID if not.
 * @param  array       $traits  Array of traits to pass to Segment.
 * @param  array       $options Array of options to pass to Segment.
 */
function identify( $user_id, $traits = [], $options = [] ){

    include( \Segment\module()->get_dir() . '/assets/spec-js-templates/identify.php' );

}


/**
 * Render a Javascript `page` call
 *
 * @since  1.0.0
 *
 * @param  string  $category    Category (or name) of event
 * @param  string  $name        Optional, but if set, category must be set as well.
 * @param  array   $properties  An array of properties to pass to Segment.
 * @param  array   $options     An array of options to pass to Segment.
 * @param  boolean $http_event  Whether or not the event is occurring over HTTP, as opposed to on page load.
 *                              This is helpful to track events that occur between page loads, like commenting.
 */
function page( $category = '', $name = '', $properties = [], $options = [], $http_event = false ){

    include( \Segment\module()->get_dir() . '/assets/spec-js-templates/page.php' );

}
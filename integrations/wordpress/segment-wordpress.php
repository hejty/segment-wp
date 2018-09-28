<?php
namespace Segment\Integrations\Wordpress;

// Load our spec functions
include_once( __DIR__ . '/specs/identify.php' );
include_once( __DIR__ . '/specs/track.php' );
include_once( __DIR__ . '/set-cookies.php' );

// Finally call the identify event
add_filter('segment_identify_event', __NAMESPACE__ . '\identify_current_user', 10, 1);


// Finally call the track event
add_filter('segment_track_events', __NAMESPACE__ . '\track_page_views', 10, 1);

// Finally call the track event
add_filter('segment_track_events', __NAMESPACE__ . '\track_user_login', 10, 1);
add_filter('segment_track_events', __NAMESPACE__ . '\track_user_registration', 10, 1);

/**
 * Uses Cookie\set_cookie() to notify Segment that a user has logged in.
 *
 * @since  1.0.0
 *
 * @param  string  $login Username of logged in user.
 * @param  WP_User $user  User object of logged in user.
 *
 */
add_action('wp_login', __NAMESPACE__ . '\set_user_login_cookie', 10, 2 );

/**
 * Uses Segment_Cookie::set_cookie() to notify Segment that a user has signed up.
 *
 * @since  1.0.0
 *
 * @param  int  $user_id Username of new user.
 *
 */
add_action('user_register', __NAMESPACE__ . '\set_user_registration_cookie', 10, 2 );
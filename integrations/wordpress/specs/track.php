<?php
namespace Segment\Integrations\Wordpress;
use Segment\Core\Cookie;
/**
 * This file serves as a placeholder for all of the individual
 * functions that will eventually be added to our Segment
 * track events.
 *
 * Note that no hooks exist in this file and thus none of these functions
 * are running here. These functions are simply defining what to track, getting
 * the appropriate data, and appending themselves to the $tracking_events array.
 */


/**
 * Page Views
 *
 * Note that all of these are checking for pages, and we don't want that to throw
 * off Google Analytics's bounce rate, so mark them `noninteraction` so that these
 * `View` Events do not tell Google an event has ocurred, which means the user will no longer
 * count as a "bounce"
 *
 * @param array $track_events The array of Segment .track() events
 * @return array $track_events
 */
function track_page_views( $track_events ){

	if( is_front_page() ){

		$track = array(
			'event' => "Viewed Home Page",

			'properties' => [
				'category'	=> 'page_view',
				'nonInteraction' => 1

			]
        );

        $track_events[] = $track;

	}

	if( is_page() && ! is_front_page() ){

		$track = array(

			'event' => sprintf( "Viewed %s Page", single_post_title( '', false ) ),

			'properties' => [
				'category'	=> 'page_view',
				'nonInteraction' => 1
			]

        );

        $track_events[] = $track;

	}

	return $track_events;

}

/**
 * User Registration
 *
 * @return void
 */
function track_user_registration( $track_events ){

	if ( Cookie\get_cookie( 'signed_up' ) ) {

		$user_id = json_decode( Cookie\get_cookie( 'signed_up' ) );
		$user = get_user_by( 'id', $user_id );

		// Filter our identify Call
		add_filter( 'segment_get_current_user_identity', __NAMESPACE__ . '\identify_new_user', 10, 1);

		$track = array(
			'event'      => 'User Signed Up',

			'properties' => array(

				'username'  => $user->user_login,
				'email'     => $user->user_email,
				'name'      => $user->display_name,
				'firstName' => $user->user_firstname,
				'lastName'  => $user->user_lastname,

			),

			'http_event' => 'signed_up'
		);

        // Add our Login track events to the array of track events
        $track_events[] = $track;

	}

    return $track_events;

}


/**
 * User Login
 *
 * @return void
 */
function track_user_login( $track_events ){

	$user = wp_get_current_user();
	$hash = md5( json_encode( $user ) );

	if ( Cookie\get_cookie( 'logged_in', $hash ) ) {

		$track = array(

			'event'      => "Logged In",

			'properties' => array(

				'username'  => $user->user_login,
				'email'     => $user->user_email,
				'name'      => $user->display_name,
				'firstName' => $user->user_firstname,
				'lastName'  => $user->user_lastname

			),

			'http_event' => 'logged_in'

        );

        // Add our Login track events to the array of track events
        $track_events[] = $track;

	}

    return $track_events;

}
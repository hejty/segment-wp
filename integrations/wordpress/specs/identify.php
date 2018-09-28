<?php
namespace Segment\Integrations\Wordpress;
use Segment\Core\Cookie;

/**
 * Based on the current user or commenter, see if we have enough information
 * to record an `identify` call. Since commenters don't have IDs, we
 * identify everyone by their email address.
 *
 * @since  1.0.0
 *
 * @return bool|array Returns false if there is no commenter or logged in user
 *                    An array of the user ID and traits if there is an authenticated user.
 */
function identify_current_user( $identify ) {

    $user      = wp_get_current_user();
    $identify  = false;

    // We've got a logged-in user.
    // http://codex.wordpress.org/Function_Reference/wp_get_current_user

    if ( is_user_logged_in() && $user ) {

        // Set our core identify values
        $identify = array(

            'user_id' => $user->ID,

            'traits'  => array(

                'username'  => $user->user_login,
                'email'     => $user->user_email,
                'firstName' => $user->user_firstname,
                'lastName'  => $user->user_lastname,
                'address'   => []

            )

        );

    }

    // If Identify exists and has data
    if ( $identify ) {

        // Clean out empty traits before sending it back.
        $identify['traits'] = array_filter( $identify['traits'] );

    }

    /**
     * Allows developers to modify the entire $identify call.
     *
     * @since 1.0.0
     */
    return apply_filters( 'segment_get_current_user_identity', $identify );

}



    /**
	 * Filters the .identify() call with the newly signed up user.
	 * This is helpful, as the user will often times not be authenticated after signing up.
	 *
	 * @since  1.0.0
	 *
	 * @param  mixed $identify   False if no user is found, array of traits and ID if a user is found.
	 * @return array $identify   Array of traits for newly signed up user.
	 */
	function identify_new_user( $identify ) {

		if ( Cookie\get_cookie( 'signed_up' ) ) {

            $user_id = json_decode( Cookie\get_cookie( 'signed_up' ) );
            $user    = get_user_by( 'id', $user_id );

			$identify = array(

                'user_id' => $user->ID,

				'traits'  => array(

					'username'  => $user->user_login,
					'email'     => $user->user_email,
					'firstName' => $user->user_firstname,
					'lastName'  => $user->user_lastname

                )

            );

        }

        return $identify;

	}
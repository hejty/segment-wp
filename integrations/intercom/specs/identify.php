<?php
namespace Segment\Integrations\Intercom;

/**
 * Filters the .identify() object if we have an authenticated user and Intercom API key is set.
 *
 * Read More Here: https://www.intercom.com/help/configure-intercom-for-your-product-or-site/staying-secure/enable-identity-verification-on-your-web-product
 *
 * @since  1.0.0
 *
 * @param  array|bool $identify   Array of user identity, if one exists.  If none exists, returns false.
 *
 * @return array                  Modified array of user identity, passed to .identify() API.
 */
function maybe_enable_segment_intercom_integration( $identify ) {

	$user_id = $identify['user_id'];
	$intercom_secure_key = get_option('segment_intercom_secure_mode_key');

	if ( ! empty( $user_id ) && ! empty( $intercom_secure_key ) ){

		$identify['options'] = isset( $identify['options'] ) ? $identify['options'] : array();

		$identify['options']['integrations']['Intercom'] = array(
			'userHash' => hash_hmac( 'sha256', $user_id, $intercom_secure_key )
        );

	}

	return $identify;
}
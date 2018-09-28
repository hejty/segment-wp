<?php
namespace Segment\Helpers\Arrays;

/**
 * Check if items a given array contains any part of the 
 * string passed in as a parameter.
 */
function array_contains_string_part( $array = [], $string = '' ){

	// All strings pssed must be lowercased
	$_string = strtolower( $string );

	// Loop through our array
	foreach( $array as $array_item ){

		// If a part of the string exists in the array
		if( strpos( $_string, $array_item ) !== FALSE ) {

			// Return true & bail
			return true;

		}

	}

}
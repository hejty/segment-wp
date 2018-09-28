<?php
namespace Segment\Helpers\Sanitize;

/**
 * Helper function, essentially a replica of stripslashes_deep, but for esc_js.
 *
 * @since 1.0.0
 *
 * @param  mixed  $value Handles arrays, strings and objects that we are trying to escape for JS.
 * @return mixed  $value esc_js()'d value.
 */
function esc_js_deep( $value ) {

    if ( is_array( $value ) ) {

        $value = array_map( __NAMESPACE__ . '\esc_js_deep', $value );

    } elseif ( is_object( $value ) ) {

        $vars = get_object_vars( $value );

        foreach ( $vars as $key => $data ) {

            $value->{$key} = esc_js_deep( $data );

        }

    } elseif ( is_string( $value ) ) {

        $value = esc_js( $value );

    }

    return $value;
}
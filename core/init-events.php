<?php

/**
 *
 */

add_action( 'wp_footer', '\Segment\Core\Specs\Identify\init_identify_event' );
add_action( 'wp_footer', '\Segment\Core\Specs\Track\init_track_events' );
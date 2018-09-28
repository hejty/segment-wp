<?php
namespace Segment\Integrations\Intercom;

include_once( __DIR__ . '/specs/identify.php' );

add_filter( 'segment_identify_event', __NAMESPACE__ . '\maybe_enable_segment_intercom_integration', 10, 1 );
<?php
namespace Segment\Core\Specs\Identify;
use Segment\Core\JsSpecCall;

function init_identify_event(){

    // By default, we do not have any identify events
    $identify = '';

    // Identify events will be added via this filter
    $identify = apply_filters( 'segment_identify_event', $identify );

    // Bail If we do not have an identify event
    if ( ! $identify )
        return;

    // If no options were passed, set an empty array
    if ( ! isset( $identify['options'] ) ) {

        $identify['options'] = array();

    }

    // Finally pass our event into our call to Segment
    JsSpecCall\identify( $identify['user_id'], $identify['traits'], $identify['options'] );

}
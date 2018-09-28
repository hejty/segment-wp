<?php
namespace Segment\Config;

return array(

    // Files to be autoloaded
    'files'	=> [

        // Helpers and conditional check functions
        'helpers/sanitize.php',
        'helpers/arrays.php',
        'helpers/environment.php',

        'admin/settings-page.php',

        // Load core functionality
        'core/cookie.php',
        'core/specs/identify.php',
        'core/specs/track.php',
        'core/load-analytics-js.php',
        'core/js-spec-call.php',
        'core/init-events.php',

        // Load WooCommerce specific tracking
        'integrations/wordpress/segment-wordpress.php',
        'integrations/intercom/segment-intercom.php',
        'integrations/woocommerce/segment-woocommerce.php',

    ]

);
<?php
namespace Segment\SpecJsTemplates\Identify;
use Segment\Helpers\Sanitize;

    // Script data formatting
    $final_user_id = esc_js( $user_id );

    $final_traits = ! empty( $traits )
                  ?  ', ' . json_encode( Sanitize\esc_js_deep( $traits ) )
                  : ', {}';

    $final_options = ! empty( $options )
                  ? ', ' . json_encode( Sanitize\esc_js_deep( $options ) )
                  : '';
?>
<script type="text/javascript">
      analytics.identify(<?= $final_user_id, $final_traits,  $final_options; ?> );
</script>
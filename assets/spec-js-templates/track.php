<?php
namespace Segment\SpecJsTemplates\Track;
use Segment\Helpers\Sanitize;

    // Script data formatting
    $final_event_name = esc_js( $event_name );

    $final_properties = ! empty( $properties )
                        ?  ', ' . json_encode( Sanitize\esc_js_deep( $properties ) )
                        : ', {}';

    $final_options = ! empty( $options )
                    ? ', ' . json_encode( Sanitize\esc_js_deep( $options ) )
                    : '';
?>
<script type="text/javascript">
      analytics.track(<?= "'{$final_event_name}'", $final_properties, $final_options; ?> );

      <?php if ( $http_event ){ ?>

            window.addEventListener('DOMContentLoaded', async () => {
                  const URL = '';

                  const data = {
				action : 'segment_unset_cookie',
				key    : '<?= esc_js( $http_event ); ?>',
			};

                  const postDataAsync = (url, data) => {
                        const config = {
                              method: 'POST',
                              headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                              },
                              body: JSON.stringify(data),
                        }

                        return fetch(url, config);
                  }

                  const response = await (await postDataAsync(URL, data)).json();
                  console.log({ response });
            });

      <?php } ?>

</script>
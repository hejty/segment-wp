<?php
namespace Segment\Helpers\Environment;
use Segment\Helpers\Arrays;
use function Segment\module as module;


/**
 * In order to check for environments, we first need to know what
 * URL we are currently browsing.
 *
 * @return string The current domain
 */
function get_domain(){

	return $_SERVER['HTTP_HOST'];

}

/**
 * When working with Segment, we need to control what API Key
 * gets loaded depending on if we are on a dev environment or
 * a production environment as we have different sources set up
 * for each environment.
 *
 * This function checks to see if we have defined dev environments by
 * comparing the current domains to a dev environments array.
 *
 *
 * @return bool Are we currently on a development environment
 */
function is_dev_environment(){

    return Arrays\array_contains_string_part(
        module()->get_config('segment_environment_urls')['dev'],
        get_domain()
    );

}

/**
 * We need to fetch various rules and API keys based on the current environment.
 * Enter this handy little function. Use it instead of explicity defining
 * `dev` or `production` when fetching settings.
 *
 * This function wraps the is_dev_environment() function and takes it
 * a step further by giving us the string of the environment we are on.
 * If it turns out we are on a dev environment, we are going
 * to check and make sure we have a dev environment key before returning anything.
 *
 * @return string $environment The type of environment we are currently on
 */
function get_environment(){

    $environment = is_dev_environment() && null !== get_option( 'segment_api_key' )
                ? 'dev'
                : 'production';

    return $environment;

}
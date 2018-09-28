<?php
/**
 * The main config file where we define the
 * settings for our module.
 *
 * @package Segment
 * @author CreativeFuse
 */

namespace Segment\Config;

// Merge all config files into a single config object
// to be loaded into our module
return array_merge(

	include_once( __DIR__ . '/_module.php' ),
	include_once( __DIR__ . '/_autoload.php' ),
	include_once( __DIR__ . '/_segment-settings.php' )

);

<?php
/**
 * Random Post Redirect
 *
 * @package           RandomPostRedirect
 * @author            Peter Wilson
 * @copyright         YYYY Peter Wilson
 * @license           MIT
 *
 * @wordpress-plugin
 * Plugin Name: Random Post Redirect
 * Description: Random Post Redirect
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 8.0
 * Author: Peter Wilson
 * Author URI: https://peterwilson.cc
 * License: MIT
 * Text Domain: random-post-redirect
 */

namespace PWCC\RandomPostRedirect;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__ . '/inc/namespace.php';

bootstrap();
register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_plugin' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate_plugin' );

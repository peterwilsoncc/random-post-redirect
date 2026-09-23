<?php
/**
 * Random Post Redirect
 *
 * @package           RandomPostRedirect
 */

namespace PWCC\RandomPostRedirect;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

const PLUGIN_VERSION = '1.0.0';

/**
 * Bootstrap the plugin.
 */
function bootstrap() {
	add_action( 'init', __NAMESPACE__ . '\\rewrite_rules' );
	add_action( 'parse_request', __NAMESPACE__ . '\\parse_request' );
}

/**
 * Activate plugin.
 *
 * Flush the rewrite rules during plugin activation.
 */
function activate_plugin() {
	// Runs late to ensure other plugin's rewrite rules are registered.
	add_action( 'init', 'flush_rewrite_rules', 1024 );
}

/**
 * Deactivate plugin.
 *
 * Flush the rewrite rules during plugin deactivation.
 */
function deactivate_plugin() {
	// Runs late to ensure other plugin's rewrite rules are registered.
	add_action( 'init', 'flush_rewrite_rules', 1024 );
}

/**
 * Add rewrite rules.
 *
 * Create the rewrite rule for enabling redirects.
 *
 * @global \WP $wp WordPress request object.
 */
function rewrite_rules() {
	// To do: rewrite rules.

	global $wp;
	$wp->add_query_var( 'random' );
}

/**
 * Parse Random requests.
 *
 * @param \WP $wp WordPress request object.
 */
function parse_request( $wp ) {
	if (
		! isset( $wp->query_vars['random'] )
	) {
		return;
	}

	$wp->set_query_var( 'order', 'RAND' );
}

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

	$wp->set_query_var( 'orderby', 'rand' );
	$wp->set_query_var( 'posts_per_page', '1' );
	$wp->set_query_var( 'update_post_meta_cache', false );
	$wp->set_query_var( 'update_post_term_cache', false );

	add_filter( 'wp_headers', __NAMESPACE__ . '\\random_redirect_headers' );
}

/**
 * Process redirect headers.
 *
 * @global \WP_Query Main query object for request.
 *
 * @param string[] $headers Associative array of headers to be sent.
 * @return string[] Modified array of headers.
 */
function random_redirect_headers( $headers ) {
	global $is_IIS;
	global $wp_query;

	if ( empty( $wp_query->posts ) ) {
		// Allow WordPress to handle the 404.
		return $headers;
	}

	$permalink = get_permalink( $wp_query->posts[0] );
	$location  = $permalink;
	$location  = wp_sanitize_redirect( $location );
	$location  = wp_validate_redirect( $location );

	if ( empty( $location ) ) {
		// Allow WordPress to handle the request.
		return $headers;
	}

	$status        = 302;
	$location      = $permalink;
	$x_redirect_by = 'WordPress';
	/** This filter is documented in /wp-includes/pluggable.php */
	$location = apply_filters( 'wp_redirect', $location, $status );
	$location = wp_sanitize_redirect( $location );

	if ( ! $location ) {
		return $headers;
	}

	if ( ! $is_IIS && 'cgi-fcgi' !== PHP_SAPI ) {
		status_header( $status ); // This causes problems on IIS and some FastCGI setups.
	}

	/** This filter is documented in /wp-includes/pluggable.php */
	$x_redirect_by = apply_filters( 'x_redirect_by', $x_redirect_by, $status, $location );
	$headers       = array_merge( $headers, wp_get_nocache_headers() );

	if ( is_string( $x_redirect_by ) ) {
		$headers['X-Redirect-By'] = $x_redirect_by;
	}

	$headers['Location'] = $location;

	return $headers;
}

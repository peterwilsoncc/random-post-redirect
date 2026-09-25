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

	$enabled = true;

	/**
	 * Filters whether random content redirects are enabled.
	 *
	 * @since 7.2.0
	 *
	 * @param bool $enabled Whether `?random` requests redirect to random content. Default true.
	 */
	$enabled = apply_filters( 'wp_enable_random_content_redirect', $enabled ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	if ( ! $enabled ) {
		return;
	}

	add_action( 'init', __NAMESPACE__ . '\\rewrite_rules' );
	// Runs early to allow plugins to filter random requests, won't be needed in Core as the order of operations will handle.
	add_action( 'parse_request', __NAMESPACE__ . '\\parse_request', 5 );
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
	// @todo: Add Rewrite rules to blog index, post type index, term index.

	global $wp;
	$wp->add_query_var( 'random' );
}

/**
 * Parse Random requests.
 *
 * Runs on the `parse_request, 5` hook.
 *
 * @param \WP $wp WordPress request object.
 */
function parse_request( $wp ) {
	if (
		! isset( $wp->query_vars['random'] )
	) {
		return;
	}

	// @todo: Decide if this needs to move to `pre_get_posts` for the purpose of the POC.
	$wp->set_query_var( 'orderby', 'rand' );
	$wp->set_query_var( 'posts_per_page', '1' );
	$wp->set_query_var( 'update_post_meta_cache', false );
	$wp->set_query_var( 'update_post_term_cache', false );

	add_filter( 'wp_headers', __NAMESPACE__ . '\\random_redirect_headers' );
}

/**
 * Process redirect headers.
 *
 * Runs on the `wp_headers` hook.
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

	$post   = $wp_query->posts[0];
	$permalink = get_permalink( $post );
	if ( empty( $permalink ) ) {
		// Allow WordPress to handle the request.
		return $headers;
	}
	$headers['X-Robots-Tag'] = 'noindex, follow';

	// Redirect after sending headers.
	add_action(
		'send_headers',
		function () use ( $post ) {
			if ( wp_safe_redirect( get_permalink( $post ), 302, 'Random Post Redirect' ) ) {
				$message = sprintf(
					'Redirecting to <a href="%1$s">%2$s</a>.',
					esc_url( get_permalink( $post ) ),
					wp_strip_all_tags( get_the_title( $post ) )
				);
				wp_die( wp_kses_post( $message ), 'Redirecting', 302 );
			}
		},
		1 // Do this early to avoid work by other plugins.
	);

	return $headers;
}

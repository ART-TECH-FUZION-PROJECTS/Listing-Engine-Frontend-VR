<?php
/**
 * URL Router Scaffold for Listing Engine Frontend.
 *
 * @package ListingEngineFrontend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generate a secure, obfuscated property detail URL.
 *
 * @param int $listing_id The property ID.
 * @return string The detail page URL or error.
 */
function lef_get_secure_detail_url( $listing_id ) {
	global $wpdb;
	
	// 1. Query wp_admin_management for 'Listing Single View' page_id.
	$table_name = $wpdb->prefix . 'admin_management';
	$page_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT page_id FROM $table_name WHERE name = %s",
		'Listing Single View'
	));

	if ( ! $page_id ) {
		return 'error_not_found';
	}

	// 2. Get the permalink of that page.
	$base_url = get_permalink( $page_id );

	// 3. Obfuscate the listing ID (Base64 as per requirement).
	$encoded_id = base64_encode( $listing_id );

	// 4. Construct the URL. 
	// Future-ready: could be pretty permalink, but for now, query param.
	return add_query_arg( 'property_ref', $encoded_id, $base_url );
}

/**
 * Decode the property reference from URL.
 *
 * @return int|bool The decoded ID or false.
 */
function lef_get_decoded_listing_id() {
	$encoded_id = isset( $_GET['property_ref'] ) ? sanitize_text_field( $_GET['property_ref'] ) : false;
	
	if ( ! $encoded_id ) {
		return false;
	}

	$decoded_id = base64_decode( $encoded_id );
	return is_numeric( $decoded_id ) ? intval( $decoded_id ) : false;
}

/**
 * Fetch the custom login URL from the admin management table.
 *
 * @return string The login URL, or fallback to default WP login URL.
 */
function lef_get_login_url() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'admin_management';
	
	// Query for the 'login' entry value.
	$login_url = $wpdb->get_var( $wpdb->prepare(
		"SELECT value FROM $table_name WHERE name = %s",
		'login'
	));

	// Return the custom URL if found and valid, else fallback to WP default.
	return $login_url ? esc_url( $login_url ) : wp_login_url();
}

<?php
/**
 * Registry Hooks for Listing Engine Frontend.
 *
 * @package ListingEngineFrontend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ─────────────────────────────────────────────────────────────
// Admin Menus Registration
// ─────────────────────────────────────────────────────────────

/* ==================== MENUS ==================== */

/**
 * Register LEF Admin Menu and Submenus.
 */
function lef_register_admin_menus() {
	// Add main menu "LEF" below Comments. Comments corresponds to position 25, so we use 26.
	add_menu_page(
		'Listing Engine Frontend',          // Page title
		'LEF',                              // Menu title
		'manage_options',                   // Capability
		'lef-main-menu',                    // Menu slug
		'lef_render_main_page',             // Callback function
		'dashicons-building',               // Icon
		26                                  // Position
	);

	// Add submenu "Dashboard" under "LEF"
	add_submenu_page(
		'lef-main-menu',                    // Parent slug
		'Listing Engine Dashboard',         // Page title
		'Dashboard',                        // Menu title
		'manage_options',                   // Capability
		'lef-dashboard',                    // Menu slug
		'lef_render_dashboard_page'         // Callback function
	);

	// Add submenu "Database" under "LEF"
	add_submenu_page(
		'lef-main-menu',                    // Parent slug
		'Database Management',              // Page title
		'Database',                         // Menu title
		'manage_options',                   // Capability
		'lef-database',                     // Menu slug
		'lef_render_database_page'          // Callback function
	);

	// Add submenu "Manage Reservations" under "LEF"
	add_submenu_page(
		'lef-main-menu',
		'Manage Reservations',
		'Manage Reserv',
		'manage_options',
		'lef-manage-reservations',
		'lef_render_manage_reservations_page'
	);
	
	// Remove the duplicate "LEF" submenu that WordPress auto-creates
	remove_submenu_page( 'lef-main-menu', 'lef-main-menu' );
}
add_action( 'admin_menu', 'lef_register_admin_menus' );

// ─────────────────────────────────────────────────────────────
// Pending Reservation Count Bubble
// ─────────────────────────────────────────────────────────────

/**
 * Inject pending reservation count bubbles on the admin menu.
 *
 * Fires at priority 999 (after all menus are registered) to update:
 *   1. The "Manage Reservations" submenu label.
 *   2. The top-level "LEF" main menu label.
 *
 * Uses WordPress-native count bubble markup so it inherits the
 * standard WP admin styling (same as Comments pending count).
 *
 * @global array $menu     WordPress global admin menu array.
 * @global array $submenu  WordPress global admin submenu array.
 * @return void
 */
function lef_inject_pending_reservation_bubble() {
	global $menu, $submenu, $wpdb;

	// ── 1. Query pending reservation count ──────────────────────
	$table = $wpdb->prefix . 'ls_reservation';

	// Guard: skip gracefully if the table does not exist yet
	// (e.g. before the plugin's DB installer has run).
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) !== $table ) {
		return;
	}

	$pending_count = (int) $wpdb->get_var(
		"SELECT COUNT(*) FROM `{$table}` WHERE `status` = 'pending'"
	);

	// Nothing pending — no bubble needed.
	if ( $pending_count <= 0 ) {
		return;
	}

	// ── 2. Build the WP-native count bubble HTML ─────────────────
	// WordPress uses this exact markup for Comments, WooCommerce, etc.
	// The CSS is already provided by wp-admin out of the box.
	$bubble = sprintf(
		' <span class="awaiting-mod count-%1$d"><span class="pending-count">%1$d</span></span>',
		$pending_count
	);

	// ── 3. Update submenu: "Manage Reservations" ─────────────────
	// $submenu[ parent_slug ] is an array of submenu entries.
	// Each entry: [ 0 => title, 1 => capability, 2 => slug, 3 => page_title ]
	if ( isset( $submenu['lef-main-menu'] ) ) {
		foreach ( $submenu['lef-main-menu'] as &$item ) {
			if ( isset( $item[2] ) && $item[2] === 'lef-manage-reservations' ) {
				$item[0] = 'Manage Reserv' . $bubble;
				break;
			}
		}
		unset( $item ); // Break the reference after the loop.
	}

	// ── 4. Update main menu: "LEF" top-level item ────────────────
	// $menu is a numerically indexed array.
	// Each entry: [ 0 => title, 1 => capability, 2 => slug, ... ]
	foreach ( $menu as &$main_item ) {
		if ( isset( $main_item[2] ) && $main_item[2] === 'lef-main-menu' ) {
			$main_item[0] = 'LEF' . $bubble;
			break;
		}
	}
	unset( $main_item ); // Break the reference.
}
add_action( 'admin_menu', 'lef_inject_pending_reservation_bubble', 999 );

// ─────────────────────────────────────────────────────────────
// Admin Pages Callbacks
// ─────────────────────────────────────────────────────────────

/* ==================== PAGES ==================== */

/**
 * Callback for Main Menu Page (Fallback if needed, redirects to dashboard)
 */
function lef_render_main_page() {
	echo '<script>window.location.replace("admin.php?page=lef-dashboard");</script>';
}

/**
 * Callback for Dashboard Submenu Page
 */
function lef_render_dashboard_page() {
	$template_path = LEF_PLUGIN_DIR . 'backend/template/dashboard.php';
	
	if ( file_exists( $template_path ) ) {
		require_once $template_path;
	} else {
		echo '<div class="wrap"><div class="error"><p>Dashboard template not found.</p></div></div>';
	}
}

/**
 * Callback for Database Submenu Page
 */
function lef_render_database_page() {
	$template_path = LEF_PLUGIN_DIR . 'backend/template/database.php';
	
	if ( file_exists( $template_path ) ) {
		require_once $template_path;
	} else {
		echo '<div class="wrap"><div class="error"><p>Database template not found.</p></div></div>';
	}
}

/**
 * Callback for Manage Reservations Submenu Page
 */
function lef_render_manage_reservations_page() {
	global $wpdb;

	$action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
	$id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

	if ( $action === 'view' && $id ) {
		// Fetch Reservation Data
		$reserv = $wpdb->get_row( $wpdb->prepare(
			"SELECT r.*, p.title as property_title, p.host_id
			 FROM {$wpdb->prefix}ls_reservation r
			 LEFT JOIN {$wpdb->prefix}ls_property p ON r.property_id = p.id
			 WHERE r.id = %d",
			$id
		), ARRAY_A );

		if ( $reserv ) {
			// Traveller Info
			$t_user      = get_userdata( $reserv['user_id'] );
			$t_full_name = get_user_meta( $reserv['user_id'], 'full_name', true );
			$t_phone     = get_user_meta( $reserv['user_id'], 'mobile_number', true );

			$reserv['traveller'] = array(
				'name'  => ! empty( $t_full_name ) ? $t_full_name : ( $t_user ? $t_user->user_login : 'Unknown' ),
				'email' => $t_user ? $t_user->user_email : 'N/A',
				'phone' => ! empty( $t_phone ) ? $t_phone : 'N/A'
			);

			// Host Info
			$h_user      = get_userdata( $reserv['host_id'] );
			$h_full_name = get_user_meta( $reserv['host_id'], 'full_name', true );
			$h_phone     = get_user_meta( $reserv['host_id'], 'mobile_number', true );

			$reserv['host'] = array(
				'name'  => ! empty( $h_full_name ) ? $h_full_name : ( $h_user ? $h_user->user_login : 'Unknown' ),
				'email' => $h_user ? $h_user->user_email : 'N/A',
				'phone' => ! empty( $h_phone ) ? $h_phone : 'N/A'
			);

			// JSON Decodes
			$reserv['dates']  = json_decode( $reserv['reserve_date'], true );
			$reserv['guests'] = json_decode( $reserv['total_guests'], true );

			$template_path = LEF_PLUGIN_DIR . 'backend/template/manage-reservation-models/view-edit.php';
		} else {
			$template_path = LEF_PLUGIN_DIR . 'backend/template/manage-reservation-models/manage-reservation.php';
		}
	} else {
		$template_path = LEF_PLUGIN_DIR . 'backend/template/manage-reservation-models/manage-reservation.php';
	}

	if ( file_exists( $template_path ) ) {
		include $template_path;
	} else {
		echo '<div class="wrap"><div class="error"><p>Template not found.</p></div></div>';
	}
}


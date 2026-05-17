<?php
/**
 * DB Handler class.
 * Manages database table status reporting and repairs based on schema definitions.
 *
 * @package ListingEngineFrontend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LEF_DB_Handler {
	/**
	 * Initialize hooks
	 */
	public static function init() {
		add_action( 'wp_ajax_lef_db_refresh', [ __CLASS__, 'ajax_refresh' ] );
		add_action( 'wp_ajax_lef_db_repair', [ __CLASS__, 'ajax_repair' ] );
	}

	/**
	 * Verify if table exists and has all the required columns based on schema
	 * 
	 * @param string $table_name Name of the table.
	 * @return array Array with status [ 'created' => bool, 'complete' => bool ]
	 */
	public static function get_table_status( $table_name ) {
		global $wpdb;
		
		$status = [
			'created'  => false,
			'complete' => false,
		];

		// 1. Check if table exists
		$exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) === $table_name;
		
		if ( ! $exists ) {
			return $status;
		}

		$status['created'] = true;

		// 2. Check complete columns (using defined schema from db-schema.php)
		if ( ! function_exists( 'lef_get_db_schemas' ) ) {
			require_once LEF_PLUGIN_DIR . 'includes/db-schema.php';
		}

		$schemas = lef_get_db_schemas();
		if ( ! isset( $schemas[ $table_name ] ) ) {
			return $status; // Schema not defined
		}

		$schema = $schemas[ $table_name ];
		
		// Extract column names from the CREATE TABLE schema
		// A simple regex to find column definitions (lines between ( and PRIMARY KEY or ending bracket)
		preg_match_all( '/^\s*([a-zA-Z0-9_]+)\s+[a-zA-Z]+/m', $schema, $matches );
		
		$expected_columns = [];
		if ( ! empty( $matches[1] ) ) {
			foreach ( $matches[1] as $col ) {
				$col_lower = strtolower( $col );
				$ignore_keywords = [ 'primary', 'key', 'unique', 'create', 'table', 'constraint', 'foreign', 'index', 'default' ];
				if ( ! in_array( $col_lower, $ignore_keywords ) ) {
					$expected_columns[] = $col_lower;
				}
			}
		}

		// Get existing columns
		$existing_columns_raw = $wpdb->get_results( "SHOW COLUMNS FROM {$table_name}" );
		$existing_columns = [];
		if ( $existing_columns_raw ) {
			foreach ( $existing_columns_raw as $col_data ) {
				$existing_columns[] = strtolower( $col_data->Field );
			}
		}

		// Verify if all expected columns exist in the table
		$missing = array_diff( $expected_columns, $existing_columns );
		
		if ( empty( $missing ) ) {
			$status['complete'] = true;
		}

		return $status;
	}

	/**
	 * AJAX Handler for fetching table status
	 */
	public static function ajax_refresh() {
		$table_name = isset( $_POST['table'] ) ? sanitize_text_field( wp_unslash( $_POST['table'] ) ) : '';

		if ( ! $table_name ) {
			wp_send_json_error( [ 'message' => 'Table name is required.' ] );
		}

		$status = self::get_table_status( $table_name );
		wp_send_json_success( $status );
	}

	/**
	 * AJAX Handler for creating or repairing table
	 */
	public static function ajax_repair() {
		$table_name = isset( $_POST['table'] ) ? sanitize_text_field( wp_unslash( $_POST['table'] ) ) : '';

		if ( ! $table_name ) {
			wp_send_json_error( [ 'message' => 'Table name is required.' ] );
		}

		if ( ! function_exists( 'lef_get_db_schemas' ) ) {
			require_once LEF_PLUGIN_DIR . 'includes/db-schema.php';
		}

		$schemas = lef_get_db_schemas();
		if ( ! isset( $schemas[ $table_name ] ) ) {
			wp_send_json_error( [ 'message' => 'Table schema not found.' ] );
		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		// dbDelta will create the table if it's missing, or add/update columns if it exists.
		dbDelta( $schemas[ $table_name ] );

		// Return fresh status
		$status = self::get_table_status( $table_name );
		wp_send_json_success( $status );
	}
}

LEF_DB_Handler::init();

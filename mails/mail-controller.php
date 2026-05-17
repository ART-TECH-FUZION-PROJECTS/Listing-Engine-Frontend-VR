<?php
/**
 * Listing Engine Frontend Centralized Email Handler (Controller)
 *
 * Sends all email notifications using WordPress wp_mail().
 * Uses config/mail-config.php for content and
 * mails/master-mail-template.php for layout.
 *
 * @package ListingEngineFrontend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load configurations.
require_once LEF_PLUGIN_DIR . 'config/mail-config.php';

class LEF_Email_Controller {

	/**
	 * Send an email based on a specific template configuration.
	 *
	 * @param string $template_id  The ID of the template from mail-config.php.
	 * @param string $to_email     Recipient email address.
	 * @param array  $dynamic_data Optional dynamic data (otp_code, reservation_id, etc.).
	 * @return bool                True if mail was sent successfully.
	 */
	public function send_email( $template_id, $to_email, $dynamic_data = array() ) {
		$config = lef_get_email_config( $template_id );

		if ( ! $config ) {
			return false;
		}

		$subject = $config['subject'];

		// If details should be fetched automatically
		if ( ! empty( $config['has_details'] ) ) {
			// Fetch User/Admin data
			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				if ( empty( $dynamic_data['user_name'] ) ) {
					$dynamic_data['user_name'] = $current_user->display_name;
				}
				if ( empty( $dynamic_data['username'] ) ) {
					$dynamic_data['username']  = $current_user->user_login;
				}
				if ( empty( $dynamic_data['email'] ) ) {
					$dynamic_data['email']     = $current_user->user_email;
				}
				if ( empty( $dynamic_data['phone'] ) ) {
					$dynamic_data['phone']     = get_user_meta( $current_user->ID, 'mobile_number', true );
				}
			}

			// If it's a reservation and reservation_id is provided, fetch more details
			if ( $template_id === 'reservation_request' && ! empty( $dynamic_data['reservation_id'] ) ) {
				global $wpdb;
				$reservation = $wpdb->get_row( $wpdb->prepare(
					"SELECT r.*, p.title as property_title, p.price as prop_price, p.host_id
					 FROM {$wpdb->prefix}ls_reservation r
					 LEFT JOIN {$wpdb->prefix}ls_property p ON r.property_id = p.id
					 WHERE r.id = %d",
					intval( $dynamic_data['reservation_id'] )
				) );

				if ( $reservation ) {
					$dynamic_data['reservation_number'] = $reservation->reservation_number;
					$dynamic_data['property_name']      = $reservation->property_title;
					$dynamic_data['total_price']         = '₹' . number_format( $reservation->total_price, 2 );
					
					$reserve_dates = json_decode( $reservation->reserve_date, true );
					if ( is_array( $reserve_dates ) ) {
						$dynamic_data['check_in']  = isset( $reserve_dates['check_in'] ) ? $reserve_dates['check_in'] : '';
						$dynamic_data['check_out'] = isset( $reserve_dates['check_out'] ) ? $reserve_dates['check_out'] : '';
					}

					$guests = json_decode( $reservation->total_guests, true );
					if ( is_array( $guests ) ) {
						$dynamic_data['guests_summary'] = sprintf(
							'%d adults, %d children, %d infants',
							isset( $guests['adults'] ) ? intval( $guests['adults'] ) : 1,
							isset( $guests['children'] ) ? intval( $guests['children'] ) : 0,
							isset( $guests['infants'] ) ? intval( $guests['infants'] ) : 0
						);
					} else {
						$dynamic_data['guests_summary'] = '1 adults, 0 children, 0 infants';
					}

					$dynamic_data['request_date'] = date( 'F j, Y', strtotime( $reservation->created_at ) );

					// Traveller details
					$user_info = get_userdata( $reservation->user_id );
					if ( $user_info ) {
						$dynamic_data['user_name']  = $user_info->display_name;
						$dynamic_data['user_email'] = $user_info->user_email;
						$phone = get_user_meta( $reservation->user_id, 'mobile_number', true );
						$dynamic_data['user_phone'] = $phone ? $phone : 'N/A';
					}

					// Host details
					$host_id = intval( $reservation->host_id );
					$host_info = $host_id ? get_userdata( $host_id ) : null;
					if ( $host_info ) {
						$dynamic_data['host_name']  = $host_info->display_name;
						$dynamic_data['host_email'] = $host_info->user_email;
						$h_phone = get_user_meta( $host_id, 'mobile_number', true );
						$dynamic_data['host_phone'] = $h_phone ? $h_phone : 'N/A';
					} else {
						$dynamic_data['host_name']  = 'N/A';
						$dynamic_data['host_email'] = 'N/A';
						$dynamic_data['host_phone'] = 'N/A';
					}

					// Property URL
					if ( function_exists( 'lef_get_secure_detail_url' ) ) {
						$dynamic_data['property_url'] = lef_get_secure_detail_url( $reservation->property_id );
					} else {
						$dynamic_data['property_url'] = home_url();
					}
					$dynamic_data['request_url']  = admin_url();
				}
			}
		}

		// Dynamically replace placeholders in subject and message body
		foreach ( $dynamic_data as $key => $val ) {
			if ( is_string( $val ) || is_numeric( $val ) ) {
				$subject           = str_replace( '{' . $key . '}', $val, $subject );
				$config['message'] = str_replace( '{' . $key . '}', $val, $config['message'] );
			}
		}

		/* Build the HTML email body from the master template */
		ob_start();
		$tpl = LEF_PLUGIN_DIR . 'mails/master-mail-template.php';
		if ( file_exists( $tpl ) ) {
			include $tpl;
		} else {
			echo wp_kses_post( $config['message'] );
		}
		$body = ob_get_clean();

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		return wp_mail( $to_email, $subject, $body, $headers );
	}
}

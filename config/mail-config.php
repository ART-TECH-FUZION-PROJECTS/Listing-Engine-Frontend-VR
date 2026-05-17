<?php
/**
 * Listing Engine Frontend Centralized Email Configurations
 *
 * Centralized configuration for all email templates sent by the plugin.
 *
 * @package ListingEngineFrontend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get configuration for a specific email template.
 *
 * @param string $template_id The identifier for the email template.
 * @return array|null Returns the config array, or null if not found.
 */
function lef_get_email_config( $template_id ) {
	$site_name   = get_bloginfo( 'name' );
	$admin_email = get_option( 'admin_email' );

	$configs = array(
		// ── OTP VERIFICATION TEMPLATES ──
		'otp_mobile_update' => array(
			'subject'     => 'OTP for Mobile Number Update',
			'title'       => 'Mobile Number Update',
			'message'     => 'You have requested to update your mobile number. To verify this change, please use the following one-time password (OTP).',
			'note'        => 'This OTP is valid for 60 seconds.',
			'has_otp'     => true,
			'has_details' => false,
		),
		'otp_email_update' => array(
			'subject'     => 'OTP for Email Address Update',
			'title'       => 'Email Address Update',
			'message'     => 'You have requested to update your email address. To verify this change, please use the following one-time password (OTP).',
			'note'        => 'This OTP is valid for 60 seconds.',
			'has_otp'     => true,
			'has_details' => false,
		),
		'otp_password_update' => array(
			'subject'     => 'OTP for Password Change',
			'title'       => 'Password Change',
			'message'     => 'You have requested to change your password. To verify this change, please use the following one-time password (OTP).',
			'note'        => 'This OTP is valid for 60 seconds.',
			'has_otp'     => true,
			'has_details' => false,
		),
		'otp_general_update' => array(
			'subject'     => 'Your Verification OTP - My Profile',
			'title'       => 'Account Verification',
			'message'     => 'To complete your profile update, please use the following one-time password (OTP).',
			'note'        => 'This OTP is valid for 60 seconds.',
			'has_otp'     => true,
			'has_details' => false,
		),

		// ── SUCCESS NOTIFICATION TEMPLATES ──
		'success_mobile_update' => array(
			'subject'     => 'Mobile Number Updated Successfully',
			'title'       => 'Mobile Number Updated',
			'message'     => 'Hello <strong>{user_name}</strong>, your mobile number has been successfully updated on your account <strong>{username}</strong>.',
			'note'        => 'Note: If you did not update your information, please contact support immediately at ' . $admin_email . '.',
			'has_otp'     => false,
			'has_details' => true,
		),
		'success_email_update' => array(
			'subject'     => 'Email Address Updated Successfully',
			'title'       => 'Email Address Updated',
			'message'     => 'Hello <strong>{user_name}</strong>, your email address <strong>{email}</strong> has been successfully added to on this account <strong>{username}</strong>. Congrats, Now you are admin of this account.',
			'note'        => 'Note: If you did not update your information, please contact support immediately at ' . $admin_email . '.',
			'has_otp'     => false, 
			'has_details' => true,
		),
		'success_password_update' => array(
			'subject'     => 'Password Changed Successfully',
			'title'       => 'Password Changed',
			'message'     => 'Hello <strong>{user_name}</strong>, your account password has been successfully changed.',
			'note'        => 'Note: If you did not perform this change, please contact support immediately at ' . $admin_email . '.',
			'has_otp'     => false,
			'has_details' => true,
		),
		'success_general_update' => array(
			'subject'     => 'Profile Updated Successfully',
			'title'       => 'Profile Updated',
			'message'     => 'Hello <strong>{user_name}</strong>, your profile information has been successfully updated on your account <strong>{username}</strong>.',
			'note'        => 'Note: If you did not update your information, please contact support immediately at ' . $admin_email . '.',
			'has_otp'     => false,
			'has_details' => true,
		),

		// ── RESERVATION TEMPLATES ──
		'reservation_request' => array(
			'subject'     => 'Reservation Request for {property_name}',
			'title'       => 'Reservation Request',
			'message'     => 'Your reservation request for the property <strong>{property_name}</strong> has been generated.',
			'note'        => 'This is an automated email from your booking system.',
			'has_otp'     => false,
			'has_details' => true,
		),
		'reservation_completed' => array(
			'subject'     => 'Thank You for Your Stay! Rate Your Experience at {property_name}',
			'title'       => 'Thank You For Choosing Us',
			'message'     => 'We hope you enjoyed your stay at <strong>{property_name}</strong>! We are committed to providing premium vacation rental experiences, and we would love to hear your feedback. Please take a moment to share your experience with us by rating your stay.',
			'note'        => 'Note: You can click the button below to directly submit a review on the property page.',
			'has_otp'     => false,
			'has_details' => true,
		)
	);

	return isset( $configs[ $template_id ] ) ? $configs[ $template_id ] : null;
}

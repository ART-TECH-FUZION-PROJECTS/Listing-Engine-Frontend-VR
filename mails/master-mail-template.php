<?php
/**
 * Listing Engine Frontend — Centralized Master Email Template
 *
 * Used for all email notifications. Driven by config/mail-config.php.
 *
 * Variables available:
 *   $config       — Array from lef_get_email_config()
 *   $dynamic_data — Array containing context-specific data (e.g., otp_code, username, check_in, total_price, etc.)
 *   $template_id  — The ID of the template from mail-config.php.
 *
 * @package ListingEngineFrontend
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site_name = get_bloginfo( 'name' );
$site_url  = esc_url( home_url() );

// Fallback safety
if ( empty( $config ) || ! is_array( $config ) ) {
	$config = array(
		'title'       => 'Notification',
		'message'     => 'You have a new notification from ' . $site_name . '.',
		'note'        => '',
		'has_otp'     => false,
		'has_details' => false,
	);
}

$dynamic_data = isset( $dynamic_data ) && is_array( $dynamic_data ) ? $dynamic_data : array();

// Dynamic Greetings
$greeting_line = 'Hello ' . ( ! empty( $dynamic_data['user_name'] ) ? esc_html( $dynamic_data['user_name'] ) : 'User' ) . ',';
if ( $template_id === 'reservation_request' ) {
	if ( ! empty( $dynamic_data['recipient_type'] ) && $dynamic_data['recipient_type'] === 'admin' ) {
		$greeting_line = 'Hi Admin,';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( $config['title'] ); ?></title>
</head>
<body style="margin:0; padding:0; background-color:#FAFAFA; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; -webkit-font-smoothing:antialiased;">

	<!-- Wrapper -->
	<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#FAFAFA; padding:20px 0;">
		<tr>
			<td align="center">
				<table width="540" cellpadding="0" cellspacing="0" style="max-width:540px; width:96%; background-color:#ffffff; border-radius:24px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.06); border:1px solid #eef2f5;">

					<!-- Header -->
					<tr>
						<td style="background-color:#F15E74; padding:35px 20px; text-align:center;">
							<h2 style="margin:0; color:#ffffff; font-size:1.3rem; font-weight:800; letter-spacing:1.5px; text-transform:uppercase;">
								<?php echo esc_html( $site_name ); ?>
							</h2>
						</td>
					</tr>

					<!-- Body -->
					<tr>
						<td style="padding:40px 30px; text-align:center;">
							<!-- Title -->
							<h1 style="margin:0 0 20px; color:#000000; font-size:1.4rem; font-weight:800; letter-spacing:-0.5px;">
								<?php echo esc_html( $config['title'] ); ?>
							</h1>

							<!-- Greeting -->
							<p style="margin:0 0 15px; color:#1A1A1A; font-size:0.95rem; line-height:1.6; text-align:left; font-weight:600;">
								<?php echo $greeting_line; ?>
							</p>

							<!-- Message Description -->
							<p style="margin:0 0 30px; color:#4B5563; font-size:0.9rem; line-height:1.6; text-align:left; font-weight:500;">
								<?php echo wp_kses( $config['message'], array( 'strong' => array() ) ); ?>
							</p>

							<!-- OTP Block -->
							<?php if ( ! empty( $config['has_otp'] ) && ! empty( $dynamic_data['otp_code'] ) ) : ?>
							<div style="background-color:#FFF1F2; border:2px solid #F15E74; border-radius:16px; padding:24px; margin:0 auto 30px; display:inline-block; min-width:220px;">
								<span style="color:#F15E74; font-size:36px; font-weight:800; letter-spacing:10px; margin-left:10px; display:inline-block;">
									<?php echo esc_html( $dynamic_data['otp_code'] ); ?>
								</span>
							</div>
							<?php endif; ?>

							<!-- Account Details Block -->
							<?php if ( ! empty( $config['has_details'] ) && $template_id !== 'reservation_request' ) : ?>
							<div style="background-color:#FAFAFA; border:1px solid #e5e7eb; border-radius:16px; padding:20px; margin:0 auto 30px; text-align:left;">
								<h3 style="font-size:15px; font-weight:700; color:#1A1A1A; margin:0 0 12px 0; border-bottom:1px solid #e5e7eb; padding-bottom:8px;">Account Details</h3>
								<p style="margin:0 0 8px; font-size:0.85rem; color:#4B5563;">
									<strong style="color:#1A1A1A;">Name:</strong> <?php echo esc_html( $dynamic_data['user_name'] ); ?>
								</p>
								<p style="margin:0 0 8px; font-size:0.85rem; color:#4B5563;">
									<strong style="color:#1A1A1A;">Username:</strong> <?php echo esc_html( $dynamic_data['username'] ); ?>
								</p>
								<p style="margin:0 0 8px; font-size:0.85rem; color:#4B5563;">
									<strong style="color:#1A1A1A;">Email:</strong> <?php echo esc_html( $dynamic_data['email'] ); ?>
								</p>
								<?php if ( ! empty( $dynamic_data['phone'] ) ) : ?>
								<p style="margin:0; font-size:0.85rem; color:#4B5563;">
									<strong style="color:#1A1A1A;">Phone:</strong> <?php echo esc_html( $dynamic_data['phone'] ); ?>
								</p>
								<?php endif; ?>
							</div>
							<?php endif; ?>

							<!-- Reservation Request Details Block -->
							<?php if ( $template_id === 'reservation_request' && ! empty( $dynamic_data['reservation_number'] ) ) : ?>
							<!-- Request Details -->
							<div style="margin-bottom:25px; padding:20px; background-color:#FAFAFA; border-radius:16px; border:1px solid #e5e7eb; text-align:left;">
								<h2 style="font-size:15px; font-weight:700; color:#1A1A1A; margin:0 0 15px 0; padding-bottom:8px; border-bottom:2px solid #F15E74;">Request Details</h2>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Reservation No:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['reservation_number'] ); ?></span>
								</div>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Check-in:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['check_in'] ); ?></span>
								</div>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Check-out:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['check_out'] ); ?></span>
								</div>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Total Guests:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['guests_summary'] ); ?></span>
								</div>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Total Price:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['total_price'] ); ?></span>
								</div>
								<div style="font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Request Date:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['request_date'] ); ?></span>
								</div>
							</div>

							<!-- Traveller Details -->
							<div style="margin-bottom:25px; padding:20px; background-color:#FAFAFA; border-radius:16px; border:1px solid #e5e7eb; text-align:left;">
								<h2 style="font-size:15px; font-weight:700; color:#1A1A1A; margin:0 0 15px 0; padding-bottom:8px; border-bottom:2px solid #F15E74;">Traveller Details</h2>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Name:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['user_name'] ); ?></span>
								</div>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Email:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['user_email'] ); ?></span>
								</div>
								<div style="font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Phone:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['user_phone'] ); ?></span>
								</div>
							</div>

							<!-- Host Details (Admin Only) -->
							<?php if ( ! empty( $dynamic_data['recipient_type'] ) && $dynamic_data['recipient_type'] === 'admin' ) : ?>
							<div style="margin-bottom:25px; padding:20px; background-color:#FAFAFA; border-radius:16px; border:1px solid #e5e7eb; text-align:left;">
								<h2 style="font-size:15px; font-weight:700; color:#1A1A1A; margin:0 0 15px 0; padding-bottom:8px; border-bottom:2px solid #F15E74;">Host Details</h2>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Name:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['host_name'] ); ?></span>
								</div>
								<div style="margin-bottom:10px; font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Email:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['host_email'] ); ?></span>
								</div>
								<div style="font-size:0.85rem; display:flex;">
									<span style="font-weight:600; color:#1A1A1A; width:140px; min-width:140px;">Phone:</span>
									<span style="color:#4B5563;"><?php echo esc_html( $dynamic_data['host_phone'] ); ?></span>
								</div>
							</div>
							<?php endif; ?>
							<?php endif; ?>

							<!-- Buttons -->
							<?php if ( ! empty( $dynamic_data['property_url'] ) ) : ?>
							<div style="text-align: center; margin: 30px 0;">
								<?php
								$btn_label = ! empty( $dynamic_data['button_label'] ) ? $dynamic_data['button_label'] : 'View Property';
								?>
								<a href="<?php echo esc_url( $dynamic_data['property_url'] ); ?>" style="display:inline-block; padding:15px 30px; font-size:14px; font-weight:700; text-decoration:none; border-radius:12px; margin:0 8px; background-color:#F15E74; color:#ffffff !important;"><?php echo esc_html( $btn_label ); ?></a>
								<?php if ( ! empty( $dynamic_data['recipient_type'] ) && $dynamic_data['recipient_type'] === 'admin' && ! empty( $dynamic_data['request_url'] ) ) : ?>
								<a href="<?php echo esc_url( $dynamic_data['request_url'] ); ?>" style="display:inline-block; padding:15px 30px; font-size:14px; font-weight:700; text-decoration:none; border-radius:12px; margin:0 8px; background-color:#2C3E50; color:#ffffff !important;">View Request</a>
								<?php endif; ?>
							</div>
							<?php endif; ?>

							<!-- Note Row -->
							<?php if ( ! empty( $config['note'] ) ) : ?>
							<div style="color:#EA0124; font-size:0.85rem; font-weight:700; line-height:1.5; margin-top:35px; border-top:1px solid #E5E7EB; padding-top:25px; text-align:center;">
								<?php echo esc_html( $config['note'] ); ?>
							</div>
							<?php endif; ?>
						</td>
					</tr>

					<!-- Footer -->
					<tr>
						<td style="padding:30px; text-align:center; font-size:13px; color:#64748B; background:#F9FAFB; font-weight:600; border-top:1px solid #e5e7eb;">
							&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
							<a href="<?php echo $site_url; ?>" style="color:#64748B; text-decoration:none;">
								<?php echo esc_html( $site_name ); ?>
							</a>
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>

</body>
</html>

<?php
/**
 * Sends all WordPress mail through the info@ mailbox (SMTP) so it actually arrives.
 * Settings: Customize > The Daily Drip > Email (SMTP). Defaults fit TransIP.
 * Nothing is sent through SMTP until a password is filled in.
 * Last failure (if any) is shown as a notice in wp-admin.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'phpmailer_init', function ( $m ) {
	$pass = tdd_get( 'smtp_pass' );
	$user = trim( tdd_get( 'smtp_user' ) );
	$host = trim( tdd_get( 'smtp_host' ) );
	if ( '' === $pass || '' === $user || '' === $host ) return;
	$port = (int) tdd_get( 'smtp_port' ) ?: 465;
	$m->isSMTP();
	$m->Host       = $host;
	$m->Port       = $port;
	$m->SMTPAuth   = true;
	$m->Username   = $user;
	$m->Password   = $pass;
	$m->SMTPSecure = 465 === $port ? 'ssl' : 'tls';
	$m->Sender     = $user;                                  // envelope sender = the real mailbox
	if ( 'yes' === tdd_get( 'smtp_force_from' ) ) $m->setFrom( $user, 'The Daily Drip', false );
} );

add_action( 'wp_mail_failed', function ( $err ) {
	update_option( 'tdd_last_mail_error', [ 'time' => current_time( 'mysql' ), 'msg' => $err->get_error_message() ], false );
} );
add_action( 'wp_mail_succeeded', function () {
	if ( get_option( 'tdd_last_mail_error' ) ) delete_option( 'tdd_last_mail_error' );
} );

add_action( 'admin_notices', function () {
	$e = get_option( 'tdd_last_mail_error' );
	if ( ! $e || ! current_user_can( 'edit_theme_options' ) ) return;
	echo '<div class="notice notice-error"><p><strong>The Daily Drip:</strong> the last email could not be sent (' . esc_html( $e['time'] ) . '): ' . esc_html( $e['msg'] ) . '. Check Customize &gt; The Daily Drip &gt; Email (SMTP).</p></div>';
} );

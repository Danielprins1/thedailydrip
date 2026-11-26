<?php
/**
 * Corporate coffee + Corporate lunch forms: sent straight from WordPress (wp_mail) to the address in
 * Customize > The Daily Drip > General ("Form answers are sent to").
 * main.js posts it in the background (admin-ajax); without JavaScript the form posts
 * to admin-post.php and returns to the page with ?sent=1.
 *
 * Tip: if emails don't arrive (or land in spam), install "WP Mail SMTP" and send
 * through the info@ mailbox. That fixes delivery on most hosting.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function tdd_handle_contact() {
	$ajax  = wp_doing_ajax();
	$fail  = function ( $msg, $code = 400 ) use ( $ajax ) {
		if ( $ajax ) wp_send_json( [ 'success' => false, 'message' => $msg ], $code );
		wp_safe_redirect( add_query_arg( 'sent', '0', wp_get_referer() ?: home_url( '/' ) ) );
		exit;
	};
	$p = wp_unslash( $_POST );

	// Spam checks: hidden honeypot field, too-fast submits, max 5 per 10 minutes per visitor
	if ( ! empty( $p['_honey'] ) ) $fail( 'spam' );
	if ( isset( $p['_t'] ) && ( time() - (int) $p['_t'] ) < 3 ) $fail( 'too fast' );
	$ip_key = 'tdd_form_' . md5( $_SERVER['REMOTE_ADDR'] ?? '' );
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 5 ) $fail( 'rate limit', 429 );

	$fields = [
		'Name'    => sanitize_text_field( $p['Name'] ?? '' ),
		'Company' => sanitize_text_field( $p['Company'] ?? '' ),
		'Email'   => sanitize_email( $p['Email'] ?? '' ),
		'Phone'   => sanitize_text_field( $p['Phone'] ?? '' ),
		'Message' => sanitize_textarea_field( $p['Message'] ?? '' ),
	];
	if ( '' === $fields['Name'] || ! is_email( $fields['Email'] ) || '' === $fields['Message'] ) $fail( 'missing fields' );

	$to      = sanitize_email( tdd_get( 'form_to' ) ) ?: get_option( 'admin_email' );
	$topics  = [ 'coffee' => 'Corporate coffee', 'lunch' => 'Corporate lunch' ];
	$subject = ( $topics[ sanitize_key( $p['topic'] ?? '' ) ] ?? 'Corporate coffee' ) . ' - website enquiry';
	$body    = '';
	foreach ( $fields as $label => $value ) {
		if ( '' !== $value ) $body .= $label . ":\n" . $value . "\n\n";
	}
	$body   .= "--\nSent from " . home_url( '/' );
	$headers = [ 'Reply-To: ' . $fields['Name'] . ' <' . $fields['Email'] . '>' ];

	if ( ! wp_mail( $to, $subject, $body, $headers ) ) $fail( 'mail failed', 500 );
	set_transient( $ip_key, $count + 1, 10 * MINUTE_IN_SECONDS );

	if ( $ajax ) wp_send_json( [ 'success' => true ] );
	wp_safe_redirect( add_query_arg( 'sent', '1', wp_get_referer() ?: home_url( '/' ) ) . '#contact' );
	exit;
}
add_action( 'wp_ajax_tdd_contact', 'tdd_handle_contact' );
add_action( 'wp_ajax_nopriv_tdd_contact', 'tdd_handle_contact' );
add_action( 'admin_post_tdd_contact', 'tdd_handle_contact' );
add_action( 'admin_post_nopriv_tdd_contact', 'tdd_handle_contact' );

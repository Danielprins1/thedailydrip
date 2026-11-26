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
	if ( '' === $fields['Name'] || '' === $fields['Company'] || '' === $fields['Phone'] || ! is_email( $fields['Email'] ) || '' === $fields['Message'] ) $fail( 'missing fields' );

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

	tdd_send_confirmation( $fields, 'nl' === ( $p['lang'] ?? '' ) ? 'nl' : 'en' );   // best effort, never blocks the enquiry

	if ( $ajax ) wp_send_json( [ 'success' => true ] );
	wp_safe_redirect( add_query_arg( 'sent', '1', wp_get_referer() ?: home_url( '/' ) ) . '#contact' );
	exit;
}
add_action( 'wp_ajax_tdd_contact', 'tdd_handle_contact' );
add_action( 'wp_ajax_nopriv_tdd_contact', 'tdd_handle_contact' );
add_action( 'admin_post_tdd_contact', 'tdd_handle_contact' );
add_action( 'admin_post_nopriv_tdd_contact', 'tdd_handle_contact' );

/**
 * Thank-you email to the visitor, from no-reply@<site domain>, with a copy of what they wrote.
 * Branded: yellow header with the logo, black text on white, yellow accents.
 */
function tdd_send_confirmation( array $f, string $lang ) {
	$host = preg_replace( '/^www\./', '', wp_parse_url( home_url(), PHP_URL_HOST ) ?: 'thedailydrip.nl' );
	$from = 'no-reply@' . $host;
	$nl   = 'nl' === $lang;
	$t    = $nl ? [
		'subject' => 'Bedankt voor je bericht | The Daily Drip',
		'hi'      => 'Hoi ' . $f['Name'] . ',',
		'thanks'  => 'Bedankt voor je mailtje!',
		'reply'   => 'We nemen zo snel mogelijk contact met je op.',
		'yours'   => 'Dit heb je ons gestuurd',
		'Name' => 'Naam', 'Company' => 'Bedrijf', 'Email' => 'E-mail', 'Phone' => 'Telefoon', 'Message' => 'Bericht',
		'auto'    => 'Dit is een automatisch bericht, je kunt hier niet op antwoorden. Mailen kan via',
	] : [
		'subject' => 'Thanks for your message | The Daily Drip',
		'hi'      => 'Hi ' . $f['Name'] . ',',
		'thanks'  => 'Thanks for your email!',
		'reply'   => "We'll get back to you as soon as we can.",
		'yours'   => 'What you sent us',
		'Name' => 'Name', 'Company' => 'Company', 'Email' => 'Email', 'Phone' => 'Phone', 'Message' => 'Message',
		'auto'    => 'This is an automatic message, please do not reply to it. You can email us at',
	];
	$info    = sanitize_email( tdd_get( 'email' ) ) ?: 'info@thedailydrip.nl';
	$logo    = esc_url( TDD_URI . '/img/email-logo.png' );
	$yellow  = '#ECB43F';
	$font    = 'font:400 16px/1.5 Arial,sans-serif;';   // one typography for the whole mail
	$blue    = '#1a56db';
	$rows    = '';
	foreach ( [ 'Name', 'Company', 'Email', 'Phone', 'Message' ] as $k ) {
		if ( '' === $f[ $k ] ) continue;
		$val = nl2br( esc_html( $f[ $k ] ) );
		if ( 'Email' === $k ) $val = '<a href="mailto:' . esc_attr( $f[ $k ] ) . '" style="color:' . $blue . '">' . $val . '</a>';
		$rows .= '<tr><td style="padding:10px 16px 10px 0;border-top:1px solid #eeeeee;vertical-align:top;width:100px;' . $font . 'color:#000000">' . esc_html( $t[ $k ] ) . '</td>'
		       . '<td style="padding:10px 0;border-top:1px solid #eeeeee;' . $font . 'color:#000000">' . $val . '</td></tr>';
	}
	$html = '<!doctype html><html><body style="margin:0;padding:0;background:#f4f4f4">'
	  . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4"><tr><td align="center" style="padding:24px 12px">'
	  . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:18px;overflow:hidden">'
	  . '<tr><td align="center" style="background:' . $yellow . ';padding:34px 20px"><img src="' . $logo . '" width="220" alt="The Daily Drip" style="display:block;border:0;width:220px;max-width:100%;height:auto"></td></tr>'
	  . '<tr><td style="padding:32px 32px 8px"><p style="margin:0 0 16px;' . $font . 'color:#000000"><strong>' . esc_html( $t['thanks'] ) . '</strong></p>'
	  . '<p style="margin:0 0 16px;' . $font . 'color:#000000">' . esc_html( $t['hi'] ) . '</p>'
	  . '<p style="margin:0 0 24px;' . $font . 'color:#000000">' . esc_html( $t['reply'] ) . '</p></td></tr>'
	  . '<tr><td style="padding:0 32px 28px"><p style="margin:0 0 8px;' . $font . 'color:#000000;border-bottom:3px solid ' . $yellow . ';display:inline-block;padding-bottom:4px">' . esc_html( $t['yours'] ) . '</p>'
	  . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $rows . '</table></td></tr>'
	  . '<tr><td style="background:#000000;padding:22px 32px"><p style="margin:0;' . $font . 'color:#ffffff">The Daily Drip<br>' . esc_html( str_replace( "\n", ', ', tdd_get( 'address' ) ) ) . '<br>' . ( $nl ? 'Ma-vr 08:00 - 16:00' : 'Mon-Fri 08:00 - 16:00' ) . '</p>'
	  . '<p style="margin:12px 0 0;' . $font . 'color:#ffffff">' . esc_html( $t['auto'] ) . ' <a href="mailto:' . esc_attr( $info ) . '" style="color:' . $yellow . '">' . esc_html( $info ) . '</a>.</p></td></tr>'
	  . '</table></td></tr></table></body></html>';

	$headers = [ 'Content-Type: text/html; charset=UTF-8', 'From: The Daily Drip <' . $from . '>', 'Reply-To: ' . $info ];
	wp_mail( $f['Email'], $t['subject'], $html, $headers );
}

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** Raw value of a Customizer field, falling back to the default in tdd_fields(). */
function tdd_get( $key ) {
	$fields  = tdd_fields();
	$default = $fields[ $key ]['default'] ?? '';
	$value   = get_theme_mod( 'tdd_' . $key, $default );
	return is_string( $value ) ? trim( $value ) : $value;
}

/** Plain text -> safe HTML, line breaks become <br>. */
function tdd_html( $text ) {
	return str_replace( [ "\r\n", "\n" ], '<br>', esc_html( $text ) );
}

/** English text of a field as HTML. */
function tdd_text( $key ) {
	return tdd_html( tdd_get( $key ) );
}

/**
 * ` data-nl="…"` attribute with the Dutch version of a field (key + "_nl").
 * $append is extra HTML added after the text in both languages (e.g. a link).
 */
function tdd_nl( $key, $append_nl = '' ) {
	$nl = tdd_get( $key . '_nl' );
	if ( '' === $nl && '' === $append_nl ) return '';
	$html = ( '' === $nl ? tdd_text( $key ) : tdd_html( $nl ) ) . $append_nl;
	return ' data-nl="' . esc_attr( $html ) . '"';
}

/** Lines of a field as <span class="line"> (for big titles); Dutch per line when given. */
function tdd_lines( $key ) {
	$en  = preg_split( '/\r?\n/', tdd_get( $key ) );
	$nl  = preg_split( '/\r?\n/', tdd_get( $key . '_nl' ) );
	$out = '';
	foreach ( $en as $i => $line ) {
		if ( '' === trim( $line ) ) continue;
		$nl_line = isset( $nl[ $i ] ) && '' !== trim( $nl[ $i ] ) ? ' data-nl="' . esc_attr( esc_html( trim( $nl[ $i ] ) ) ) . '"' : '';
		$out    .= '<span class="line"' . $nl_line . '>' . esc_html( trim( $line ) ) . '</span>';
	}
	return $out;
}

/**
 * <img> for a Customizer image field. Uses the uploaded image (with srcset) or the
 * theme's default photo in /img (with its -sm version for phones when it exists).
 */
function tdd_img( $key, $default_file, $alt = '', $sizes = '100vw' ) {
	$id = absint( get_theme_mod( 'tdd_' . $key ) );
	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image( $id, 'full', false, [ 'alt' => $alt, 'sizes' => $sizes, 'loading' => 'eager', 'decoding' => 'async' ] );
	}
	$src    = TDD_URI . '/img/' . $default_file;
	$sm     = preg_replace( '/\.webp$/', '-sm.webp', $default_file );
	$srcset = file_exists( TDD_DIR . '/img/' . $sm )
		? ' srcset="' . esc_url( TDD_URI . '/img/' . $sm ) . ' 1000w, ' . esc_url( $src ) . ' 2000w" sizes="' . esc_attr( $sizes ) . '"'
		: '';
	return '<img src="' . esc_url( $src ) . '"' . $srcset . ' alt="' . esc_attr( $alt ) . '">';
}

/** URL of the page that uses one of our templates ('menu', 'company-coffee' or 'corporate-lunch'). */
function tdd_page_url( $which ) {
	static $cache = [];
	if ( isset( $cache[ $which ] ) ) return $cache[ $which ];
	$pages = get_posts( [
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'template-' . $which . '.php',
		'posts_per_page' => 1,
		'fields'         => 'ids',
	] );
	return $cache[ $which ] = $pages ? get_permalink( $pages[0] ) : home_url( '/' . $which . '/' );
}

/** ' aria-current="page"' when we're on that page. */
function tdd_current( $which ) {
	$on = 'home' === $which ? is_front_page() : is_page_template( 'template-' . $which . '.php' );
	return $on ? ' aria-current="page"' : '';
}

/** Hours line for the hero corners, e.g. "MON–FRI<br>08:00 – 16:00". */
function tdd_hours_corner() {
	return '<p class="corner corner--tr"' . tdd_nl( 'hours_short' ) . '>' . tdd_text( 'hours_short' ) . '</p>';
}

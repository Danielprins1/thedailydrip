<?php
/**
 * The Daily Drip - theme setup.
 *
 * Content that the client edits:
 *  - Texts & photos: Appearance > Customize > "The Daily Drip" panels (EN + NL per text)
 *  - Menu card:      "Menu card" in the admin sidebar (one item per drink / sandwich / bake)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TDD_VERSION', '1.0.1' );
define( 'TDD_DIR', get_template_directory() );
define( 'TDD_URI', get_template_directory_uri() );

require TDD_DIR . '/inc/helpers.php';
require TDD_DIR . '/inc/customizer.php';
require TDD_DIR . '/inc/menu-items.php';
require TDD_DIR . '/inc/contact-form.php';
require TDD_DIR . '/inc/setup.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', [ 'script', 'style', 'search-form', 'gallery', 'caption' ] );
	add_theme_support( 'post-thumbnails' );
} );

/* Browser-tab titles: "Page | The Daily Drip" (no dashes) */
add_filter( 'document_title_separator', fn() => '|' );

/* ---------- Styles & scripts ---------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'tdd-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter+Tight:wght@300;400;500&display=swap', [], null );
	if ( is_front_page() ) {
		// Adobe Fonts kit (Arpona Sans for the payoff) - only works on domains listed in the Adobe web project
		wp_enqueue_style( 'tdd-adobe-fonts', 'https://use.typekit.net/gxb6fin.css', [], null );
	}
	wp_enqueue_style( 'tdd-style', get_stylesheet_uri(), [], TDD_VERSION );

	wp_enqueue_script( 'lenis', 'https://unpkg.com/lenis@1.1.13/dist/lenis.min.js', [], null, true );
	wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], null, true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', [ 'gsap' ], null, true );
	wp_enqueue_script( 'tdd-main', TDD_URI . '/assets/main.js', [ 'lenis', 'gsap', 'gsap-scrolltrigger' ], TDD_VERSION, true );
	// Tells main.js to send forms to WordPress instead of FormSubmit
	wp_add_inline_script( 'tdd-main', 'window.TDD = ' . wp_json_encode( [ 'ajaxUrl' => admin_url( 'admin-ajax.php' ) ] ) . ';', 'before' );
} );

add_action( 'wp_head', function () {
	echo '<link rel="preload" href="' . esc_url( TDD_URI . '/fonts/coolvetica-rg.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	$desc = tdd_get( 'seo_description' );
	if ( $desc ) echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<link rel="icon" href="' . esc_url( TDD_URI . '/img/logo.svg' ) . '" type="image/svg+xml">' . "\n";
}, 1 );

/* When logged in, push the fixed logo + sticky menu tabs below the WordPress admin bar */
add_action( 'wp_head', function () {
	if ( is_admin_bar_showing() ) {
		echo '<style>body.admin-bar .logo{margin-top:32px}body.admin-bar .carte-tabs{top:32px}@media (max-width:782px){body.admin-bar .logo{margin-top:46px}body.admin-bar .carte-tabs{top:0}}</style>' . "\n";
	}
}, 99 );

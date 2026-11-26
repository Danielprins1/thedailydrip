<?php
/**
 * One-time setup when the theme is activated:
 *  - makes sure there is a "Menu" and a "Company coffee" page using our templates
 *    (an existing page with slug menu / company-coffee is reused, its content is kept but not shown)
 *  - fills the Menu card with the current menu if it's still empty
 * The home page is front-page.php, so it works whatever is set under Settings > Reading.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function tdd_setup_pages() {
	$pages = [
		'menu'           => 'Menu',
		'company-coffee' => 'Company coffee',
	];
	foreach ( $pages as $slug => $title ) {
		$template = 'template-' . $slug . '.php';
		$using = get_posts( [ 'post_type' => 'page', 'post_status' => 'any', 'meta_key' => '_wp_page_template', 'meta_value' => $template, 'posts_per_page' => 1, 'fields' => 'ids' ] );
		if ( $using ) continue;
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $page && 'page' !== $page->post_type ) $page = null;   // get_page_by_path() also matches attachments (e.g. a Menu.pdf)
		$id   = $page ? $page->ID : wp_insert_post( [ 'post_type' => 'page', 'post_title' => $title, 'post_name' => $slug, 'post_status' => 'publish' ] );
		if ( $id && ! is_wp_error( $id ) ) update_post_meta( $id, '_wp_page_template', $template );
	}
}

function tdd_seed_menu() {
	if ( get_posts( [ 'post_type' => 'tdd_menu_item', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ] ) ) return;
	foreach ( tdd_menu_seed() as $section => $items ) {
		foreach ( array_values( $items ) as $i => $item ) {
			[ $name, $desc ] = is_array( $item ) ? $item : [ $item, '' ];
			$id = wp_insert_post( [ 'post_type' => 'tdd_menu_item', 'post_title' => $name, 'post_status' => 'publish', 'menu_order' => ( $i + 1 ) * 10 ] );
			if ( ! $id || is_wp_error( $id ) ) continue;
			tdd_set_section( $id, $section );
			if ( $desc ) update_post_meta( $id, '_tdd_desc', $desc );
		}
	}
}

add_action( 'after_switch_theme', function () {
	tdd_setup_pages();
	tdd_seed_menu();
	flush_rewrite_rules();
} );

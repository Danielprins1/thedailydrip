<?php
/**
 * "Menu card" in the admin: one item per drink, sandwich, salad or bake.
 * Title = name on the menu (text in brackets at the end is shown smaller, e.g. "(grillworst)").
 * Section + description are set in the box under the title; "Order" (Page attributes) sets the position.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function tdd_menu_sections() {
	return [
		'drinks'     => 'Drinks',
		'sandwiches' => 'Lunch - Sandwiches',
		'salads'     => 'Lunch - Salads',
		'bakes'      => 'Bakes',
	];
}

add_action( 'init', function () {
	register_post_type( 'tdd_menu_item', [
		'labels'       => [
			'name'          => 'Menu card',
			'singular_name' => 'Menu item',
			'add_new'       => 'Add item',
			'add_new_item'  => 'Add menu item',
			'edit_item'     => 'Edit menu item',
			'all_items'     => 'All items',
		],
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-coffee',
		'menu_position'=> 20,
		'supports'     => [ 'title', 'page-attributes' ],
		'hierarchical' => false,
	] );
} );

/* ---------- Edit screen: section + description ---------- */
add_action( 'add_meta_boxes_tdd_menu_item', function () {
	add_meta_box( 'tdd_menu_item_box', 'Menu details', function ( $post ) {
		wp_nonce_field( 'tdd_menu_item', 'tdd_menu_item_nonce' );
		$section = get_post_meta( $post->ID, '_tdd_section', true ) ?: 'drinks';
		$desc    = get_post_meta( $post->ID, '_tdd_desc', true );
		echo '<p><label for="tdd_section"><strong>Section</strong></label><br><select name="tdd_section" id="tdd_section">';
		foreach ( tdd_menu_sections() as $k => $label ) {
			echo '<option value="' . esc_attr( $k ) . '"' . selected( $section, $k, false ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select></p>';
		echo '<p><label for="tdd_desc"><strong>Description</strong> (optional, shown for sandwiches and salads)</label><br>';
		echo '<textarea name="tdd_desc" id="tdd_desc" rows="3" style="width:100%">' . esc_textarea( $desc ) . '</textarea></p>';
		echo '<p class="description">Position on the menu: set "Order" in the Page attributes box (lower = higher up).</p>';
	}, 'tdd_menu_item', 'normal', 'high' );
} );

add_action( 'save_post_tdd_menu_item', function ( $post_id ) {
	if ( ! isset( $_POST['tdd_menu_item_nonce'] ) || ! wp_verify_nonce( $_POST['tdd_menu_item_nonce'], 'tdd_menu_item' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	$section = sanitize_key( $_POST['tdd_section'] ?? 'drinks' );
	if ( isset( tdd_menu_sections()[ $section ] ) ) tdd_set_section( $post_id, $section );
	update_post_meta( $post_id, '_tdd_desc', sanitize_textarea_field( wp_unslash( $_POST['tdd_desc'] ?? '' ) ) );
} );

/** Saves the section + a number so the admin list can sort Drinks > Sandwiches > Salads > Bakes. */
function tdd_set_section( $post_id, $section ) {
	update_post_meta( $post_id, '_tdd_section', $section );
	update_post_meta( $post_id, '_tdd_sort', array_search( $section, array_keys( tdd_menu_sections() ), true ) + 1 );
}

/* ---------- Admin list: show section + order, sorted like the menu, filter per section ---------- */
add_filter( 'manage_tdd_menu_item_posts_columns', function ( $cols ) {
	return [ 'cb' => $cols['cb'], 'title' => 'Name', 'tdd_section' => 'Section', 'tdd_order' => 'Order' ];
} );
add_action( 'manage_tdd_menu_item_posts_custom_column', function ( $col, $id ) {
	if ( 'tdd_section' === $col ) echo esc_html( tdd_menu_sections()[ get_post_meta( $id, '_tdd_section', true ) ] ?? '-' );
	if ( 'tdd_order' === $col ) echo (int) get_post_field( 'menu_order', $id );
}, 10, 2 );
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() || ! $q->is_main_query() || 'tdd_menu_item' !== $q->get( 'post_type' ) ) return;
	$section = sanitize_key( $_GET['tdd_section'] ?? '' );
	if ( isset( tdd_menu_sections()[ $section ] ) ) {
		$q->set( 'meta_query', [ [ 'key' => '_tdd_section', 'value' => $section ] ] );
	}
	if ( ! $q->get( 'orderby' ) ) {
		$q->set( 'meta_key', '_tdd_sort' );
		$q->set( 'orderby', [ 'meta_value_num' => 'ASC', 'menu_order' => 'ASC' ] );
	}
} );
add_filter( 'edit_tdd_menu_item_per_page', fn() => 100 );
add_filter( 'views_edit-tdd_menu_item', function ( $views ) {
	$current = sanitize_key( $_GET['tdd_section'] ?? '' );
	foreach ( tdd_menu_sections() as $k => $label ) {
		$url = add_query_arg( [ 'post_type' => 'tdd_menu_item', 'tdd_section' => $k ], admin_url( 'edit.php' ) );
		$views[ 'tdd_' . $k ] = '<a href="' . esc_url( $url ) . '"' . ( $current === $k ? ' class="current"' : '' ) . '>' . esc_html( $label ) . '</a>';
	}
	return $views;
} );

/* ---------- Front end ---------- */
function tdd_menu_items( $section ) {
	return get_posts( [
		'post_type'      => 'tdd_menu_item',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_key'       => '_tdd_section',
		'meta_value'     => $section,
		'orderby'        => [ 'menu_order' => 'ASC', 'title' => 'ASC' ],
	] );
}

/** Name with a trailing "(…)" shown smaller, like on the printed menu. */
function tdd_menu_name( $post ) {
	$name = esc_html( get_the_title( $post ) );
	return preg_replace( '/\s*(\([^)]*\))\s*$/', ' <small>$1</small>', $name );
}

/** <ul> of a section; $with_desc for sandwiches/salads. */
function tdd_menu_list( $section, $with_desc = false ) {
	$items = tdd_menu_items( $section );
	if ( ! $items ) return '';
	$out = '<ul class="carte__list' . ( $with_desc ? ' carte__list--desc' : ' carte__list--grid' ) . '">';
	foreach ( $items as $item ) {
		$desc = $with_desc ? get_post_meta( $item->ID, '_tdd_desc', true ) : '';
		$out .= '<li><span class="carte__name">' . tdd_menu_name( $item ) . '</span>';
		if ( $desc ) $out .= '<span class="carte__desc">' . esc_html( $desc ) . '</span>';
		$out .= '</li>';
	}
	return $out . '</ul>';
}

/** The current menu, used to fill the Menu card once when the theme is activated. */
function tdd_menu_seed() {
	return [
		'drinks'     => [ 'americano', 'espresso', 'cappuccino', 'flat white', 'latte', 'cortado', 'chai latte', 'craft cacao', 'matcha latte', 'tea’s', 'filter batch brew', 'cold brew' ],
		'sandwiches' => [
			[ 'Classic Cheese', "KEF's old cheese, lettuce and whipped butter" ],
			[ 'Grilled Chicken Sausage (grillworst)', 'With honey mustard dressing, whipped butter and rocket' ],
			[ 'Chicken Curry', 'Chicken roulade with curry mayonaise, crumbled bacon and lettuce' ],
			[ 'Tokyo Tuna', 'With homemade tuna salad, pickled cucumber, spring onion and furikake' ],
		],
		'salads'     => [
			[ 'Caesar', 'With romaine salad, grilled chicken, homemade caesar dressing, boiled egg and croutons' ],
			[ 'Mango Feta', 'With baby spinach, maple syrup dressing, mango, white cheese, cucumber and walnuts' ],
		],
		'bakes'      => [ 'Powerbar', 'Yoghurt', 'Bananabread', 'Poppy seed loaf', 'Chocolate chip cookie', 'Oat Honey cookie', 'Croissant', 'Almond croissant', 'Cinnamon swirl', 'Butter cruffin', 'Patisserie of the day' ],
	];
}

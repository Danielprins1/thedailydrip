<?php
/**
 * Texts & photos, editable in Appearance > Customize.
 * Every text has an English field and a Dutch field ("… (NL)"); the NL/EN toggle on
 * the site switches between them. Line breaks in a field become line breaks on the site.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function tdd_sections() {
	return [
		'tdd_general' => 'General (address, hours, contact)',
		'tdd_home'    => 'Home page',
		'tdd_menu'    => 'Menu page',
		'tdd_company' => 'Corporate coffee page',
		'tdd_lunch'   => 'Corporate lunch page',
	];
}

function tdd_fields() {
	static $f = null;
	if ( null !== $f ) return $f;
	$f = [
		/* ---------- General ---------- */
		'address'         => [ 'tdd_general', 'Address', 'textarea', "Antonio Vivaldistraat 58\n1083 HP Amsterdam" ],
		'hours'           => [ 'tdd_general', 'Opening hours (footer)', 'textarea', "Monday to Friday\n08:00 – 16:00" ],
		'hours_nl'        => [ 'tdd_general', 'Opening hours (footer) (NL)', 'textarea', "Maandag t/m vrijdag\n08:00 – 16:00" ],
		'hours_short'     => [ 'tdd_general', 'Opening hours, short (photo corners)', 'textarea', "MON–FRI\n08:00 – 16:00" ],
		'hours_short_nl'  => [ 'tdd_general', 'Opening hours, short (NL)', 'textarea', "MA–VR\n08:00 – 16:00" ],
		'email'           => [ 'tdd_general', 'Email address (shown on the site)', 'email', 'info@thedailydrip.nl' ],
		'instagram'       => [ 'tdd_general', 'Instagram link', 'url', 'https://www.instagram.com/thedailydrip_zuidas/' ],
		'slogan'          => [ 'tdd_general', 'Slogan in the photo corners', 'textarea', "HUSTLE,\nDRIP, SIP." ],
		'seo_description' => [ 'tdd_general', 'Google description (meta description)', 'textarea', 'Specialty coffee and fresh lunch in Amsterdam Zuidas. Antonio Vivaldistraat 58. Monday to Friday, 08:00–16:00.' ],

		/* ---------- Home ---------- */
		'payoff'          => [ 'tdd_home', 'Payoff under the logo', 'text', 'Specialty Coffee and Sandwiches' ],
		'hero_bg'         => [ 'tdd_home', 'Hero: background photo', 'image', '' ],
		'hero_card'       => [ 'tdd_home', 'Hero: photo that rises up (portrait)', 'image', '' ],
		'mission_label'   => [ 'tdd_home', 'Intro: small label', 'textarea', "OUR\nDRIP" ],
		'mission_label_nl'=> [ 'tdd_home', 'Intro: small label (NL)', 'textarea', "ONZE\nDRIP" ],
		'mission_text'    => [ 'tdd_home', 'Intro: text', 'textarea', "In the fast lane of the Zuidas, we're the pause button. One perfectly balanced cup between meetings, a fresh lunch before the next deadline. Every bean is ground, brewed and dialled in, so your day keeps its rhythm." ],
		'mission_text_nl' => [ 'tdd_home', 'Intro: text (NL)', 'textarea', 'Op de snelweg van de Zuidas zijn wij de pauzeknop. Eén perfect gebalanceerde kop tussen meetings door, een verse lunch voor de volgende deadline. Elke boon wordt gemalen, gezet en precies afgesteld, zodat jouw dag in het ritme blijft.' ],
		'menu_button'     => [ 'tdd_home', 'Button to the menu', 'text', 'See menu' ],
		'menu_button_nl'  => [ 'tdd_home', 'Button to the menu (NL)', 'text', 'Bekijk menu' ],
		'mission_1'       => [ 'tdd_home', 'Intro: photo 1 (large, middle)', 'image', '' ],
		'mission_2'       => [ 'tdd_home', 'Intro: photo 2 (small, left)', 'image', '' ],
		'mission_3'       => [ 'tdd_home', 'Intro: photo 3 (small, right)', 'image', '' ],
		'statement_bg'    => [ 'tdd_home', 'Statement: background photo', 'image', '' ],
		'statement_title' => [ 'tdd_home', 'Statement: big title (one line per row)', 'textarea', "More espresso,\nless depresso" ],
		'statement_text'  => [ 'tdd_home', 'Statement: text', 'textarea', 'Carefully ground, brewed and balanced. Your daily boost, from the first espresso to the last afternoon flat white.' ],
		'statement_text_nl'=> [ 'tdd_home', 'Statement: text (NL)', 'textarea', 'Zorgvuldig gemalen, gezet en in balans. Je dagelijkse boost, van de eerste espresso tot de laatste flat white van de middag.' ],

		/* ---------- Menu page ---------- */
		'menu_bg'         => [ 'tdd_menu', 'Header photo', 'image', '' ],
		'menu_title'      => [ 'tdd_menu', 'Page title', 'text', 'Menu' ],
		'drinks_note'     => [ 'tdd_menu', 'Note under the drinks', 'textarea', 'various milk alternatives available' ],
		'menu_info'       => [ 'tdd_menu', 'How to edit the menu items', 'info', 'The drinks, sandwiches, salads and bakes themselves are edited under "Menu card" in the WordPress admin sidebar.' ],

		/* ---------- Corporate coffee ---------- */
		'company_bg'      => [ 'tdd_company', 'Header photo', 'image', '' ],
		'company_label'   => [ 'tdd_company', 'Small label', 'textarea', "CORPORATE\nCOFFEE" ],
		'company_label_nl'=> [ 'tdd_company', 'Small label (NL)', 'textarea', "KANTOOR\nKOFFIE" ],
		'company_title'   => [ 'tdd_company', 'Big title (one line per row)', 'textarea', "Good coffee,\nat the office too" ],
		'company_title_nl'=> [ 'tdd_company', 'Big title (NL, one line per row)', 'textarea', "Goede koffie,\nook op kantoor" ],
		'company_text'    => [ 'tdd_company', 'Intro text', 'textarea', "Want The Daily Drip coffee at your office too? Interested? Send us an email using the form below and we'll get back to you." ],
		'company_text_nl' => [ 'tdd_company', 'Intro text (NL)', 'textarea', 'Wil je de koffie van The Daily Drip ook op jouw kantoor? Interesse? Stuur ons een mailtje via het formulier hieronder, dan nemen we contact met je op.' ],
		'company_button'  => [ 'tdd_company', 'Button to the form', 'text', 'Email us' ],
		'company_button_nl'=> [ 'tdd_company', 'Button to the form (NL)', 'text', 'Mail ons' ],
		/* ---------- Corporate lunch ---------- */
		'lunch_bg'        => [ 'tdd_lunch', 'Header photo', 'image', '' ],
		'lunch_label'     => [ 'tdd_lunch', 'Small label', 'textarea', "CORPORATE\nLUNCH" ],
		'lunch_label_nl'  => [ 'tdd_lunch', 'Small label (NL)', 'textarea', "ZAKELIJKE\nLUNCH" ],
		'lunch_title'     => [ 'tdd_lunch', 'Big title (one line per row)', 'textarea', "Fresh lunch,\nfor the whole team" ],
		'lunch_title_nl'  => [ 'tdd_lunch', 'Big title (NL, one line per row)', 'textarea', "Verse lunch,\nvoor het hele team" ],
		'lunch_text'      => [ 'tdd_lunch', 'Intro text', 'textarea', "Lunch for your team, a meeting or an event? Our sandwiches and salads, for the whole office. Interested? Send us an email using the form below and we'll get back to you." ],
		'lunch_text_nl'   => [ 'tdd_lunch', 'Intro text (NL)', 'textarea', 'Lunch voor je team, een meeting of een event? Onze broodjes en salades, voor het hele kantoor. Interesse? Stuur ons een mailtje via het formulier hieronder, dan nemen we contact met je op.' ],
		'lunch_button'    => [ 'tdd_lunch', 'Button to the form', 'text', 'Email us' ],
		'lunch_button_nl' => [ 'tdd_lunch', 'Button to the form (NL)', 'text', 'Mail ons' ],

		/* ---------- Both forms (corporate coffee + lunch) ---------- */
		'form_title'      => [ 'tdd_general', 'Corporate forms: title', 'text', 'Interested?' ],
		'form_title_nl'   => [ 'tdd_general', 'Corporate forms: title (NL)', 'text', 'Interesse?' ],
		'form_text'       => [ 'tdd_general', 'Corporate forms: text', 'textarea', "Leave your details and we'll email you back as soon as we can." ],
		'form_text_nl'    => [ 'tdd_general', 'Corporate forms: text (NL)', 'textarea', 'Laat je gegevens achter, dan mailen we je zo snel mogelijk terug.' ],
		'form_to'         => [ 'tdd_general', 'Form answers are sent to (corporate coffee + lunch)', 'email', 'info@thedailydrip.nl' ],
	];
	foreach ( $f as $k => $v ) {
		$f[ $k ] = [ 'section' => $v[0], 'label' => $v[1], 'type' => $v[2], 'default' => $v[3] ];
	}
	return $f;
}

add_action( 'customize_register', function ( WP_Customize_Manager $wp ) {
	$wp->add_panel( 'tdd', [ 'title' => 'The Daily Drip', 'priority' => 1 ] );
	foreach ( tdd_sections() as $id => $title ) {
		$wp->add_section( $id, [ 'title' => $title, 'panel' => 'tdd' ] );
	}
	foreach ( tdd_fields() as $key => $field ) {
		$id = 'tdd_' . $key;
		if ( 'info' === $field['type'] ) {
			$wp->add_setting( $id, [ 'sanitize_callback' => '__return_empty_string' ] );
			$wp->add_control( $id, [ 'section' => $field['section'], 'label' => $field['label'], 'description' => $field['default'], 'type' => 'hidden' ] );
			continue;
		}
		$sanitize = [
			'image'    => 'absint',
			'email'    => 'sanitize_email',
			'url'      => 'esc_url_raw',
			'textarea' => 'sanitize_textarea_field',
			'text'     => 'sanitize_text_field',
		][ $field['type'] ];
		$wp->add_setting( $id, [ 'default' => $field['default'], 'sanitize_callback' => $sanitize ] );
		if ( 'image' === $field['type'] ) {
			$wp->add_control( new WP_Customize_Media_Control( $wp, $id, [
				'section'     => $field['section'],
				'label'       => $field['label'],
				'mime_type'   => 'image',
				'description' => 'Leave empty to use the standard photo. Large photos: at least 2000px wide.',
			] ) );
		} else {
			$wp->add_control( $id, [ 'section' => $field['section'], 'label' => $field['label'], 'type' => $field['type'] ] );
		}
	}
} );

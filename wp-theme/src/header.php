<?php if ( ! defined( 'ABSPATH' ) ) exit;
$email     = tdd_get( 'email' );
$instagram = tdd_get( 'instagram' );
$logo      = esc_url( TDD_URI . '/img/logo.svg' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

  <!-- Fixed logo: fades in on load, fades out past the first section -->
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="The Daily Drip">
    <img src="<?php echo $logo; ?>" alt="The Daily Drip" onerror="this.remove()">
    <span class="logo__placeholder">THE DAILY DRIP</span>
    <?php if ( is_front_page() && tdd_get( 'payoff' ) ) : ?>
    <span class="logo__payoff"><?php echo esc_html( tdd_get( 'payoff' ) ); ?></span>
    <?php endif; ?>
  </a>

  <!-- Menu overlay (phones: opened from the pill hamburger) -->
  <nav class="menu" id="menu-overlay" aria-hidden="true">
    <ol class="menu__list">
      <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo tdd_current( 'home' ); ?>>Home</a></li>
      <li><a href="<?php echo esc_url( tdd_page_url( 'menu' ) ); ?>"<?php echo tdd_current( 'menu' ); ?>>Menu</a></li>
      <li><a href="<?php echo esc_url( tdd_page_url( 'company-coffee' ) ); ?>"<?php echo tdd_current( 'company-coffee' ); ?> data-nl="Kantoorkoffie">Company coffee</a></li>
    </ol>
    <button class="menu__lang js-lang" lang="nl" aria-label="Nederlands">Nederlands</button>
    <div class="menu__foot">
      <p><?php echo tdd_text( 'address' ); ?></p>
      <p<?php echo tdd_nl( 'hours_short' ); ?>><?php echo tdd_text( 'hours_short' ); ?></p>
      <p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a><?php if ( $instagram ) : ?><br><a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?></p>
    </div>
  </nav>

  <!-- Floating pill nav, fixed at the bottom of the screen. Hamburger only on phones. -->
  <header class="pill" aria-label="Main">
    <a class="pill__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo $logo; ?>" alt="The Daily Drip"></a>
    <button class="pill__burger js-menu-btn" aria-label="Open menu" aria-controls="menu-overlay" aria-expanded="false"><span></span><span></span><span></span></button>
    <nav class="pill__links">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo tdd_current( 'home' ); ?>>Home</a>
      <a href="<?php echo esc_url( tdd_page_url( 'menu' ) ); ?>"<?php echo tdd_current( 'menu' ); ?>>Menu</a>
    </nav>
    <button class="pill__lang js-lang" lang="nl" aria-label="Nederlands">NL</button>
    <a class="pill__cta" href="<?php echo esc_url( tdd_page_url( 'company-coffee' ) ); ?>"<?php echo tdd_current( 'company-coffee' ); ?> data-nl="Kantoorkoffie">Company coffee</a>
  </header>

  <main id="top">

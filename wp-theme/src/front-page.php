<?php if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
$menu_url = esc_url( tdd_page_url( 'menu' ) );
$btn_en   = '<a href="' . $menu_url . '" class="tag">' . esc_html( tdd_get( 'menu_button' ) ) . '</a>';
$btn_nl   = '<a href="' . $menu_url . '" class="tag">' . esc_html( tdd_get( 'menu_button_nl' ) ?: tdd_get( 'menu_button' ) ) . '</a>';
?>

    <!-- 1. HERO - pinned, background slow, photo card rises through -->
    <section class="hero">
      <div class="hero__sticky">
        <div class="hero__bg media">
          <?php echo tdd_img( 'hero_bg', 'hero-bg.webp' ); ?>
        </div>
        <p class="corner corner--tl"><?php echo tdd_text( 'slogan' ); ?></p>
        <?php echo tdd_hours_corner(); ?>
        <div class="hero__card media">
          <?php echo tdd_img( 'hero_card', 'hero-card.webp', 'The Daily Drip', '(max-width: 760px) 72vw, 38vw' ); ?>
        </div>
      </div>
    </section>

    <!-- 2. MISSION - words light up while scrolling, photos drift -->
    <section class="mission" data-logo="dark">
      <div class="mission__inner">
        <p class="label"<?php echo tdd_nl( 'mission_label' ); ?>><?php echo tdd_text( 'mission_label' ); ?></p>
        <p class="mission__text js-words"<?php echo tdd_nl( 'mission_text', ' ' . $btn_nl ); ?>><?php echo tdd_text( 'mission_text' ) . ' ' . $btn_en; ?></p>
      </div>
      <div class="mission__photos">
        <div class="media float float--a" data-speed="-0.25"><?php echo tdd_img( 'mission_1', 'mission-1.webp', '', '(max-width: 760px) 78vw, 38vw' ); ?></div>
        <div class="media float float--b" data-speed="0.15"><?php echo tdd_img( 'mission_2', 'mission-2.webp', '', '(max-width: 760px) 45vw, 20vw' ); ?></div>
        <div class="media float float--c" data-speed="-0.45"><?php echo tdd_img( 'mission_3', 'mission-3.webp', '', '(max-width: 760px) 45vw, 22vw' ); ?></div>
      </div>
    </section>

    <!-- 3. STATEMENT - title over full-bleed photo -->
    <section class="statement">
      <div class="statement__bg media">
        <?php echo tdd_img( 'statement_bg', 'statement-bg.webp' ); ?>
      </div>
      <h2 class="statement__title"><?php echo tdd_lines( 'statement_title' ); ?></h2>
      <div class="statement__side">
        <p<?php echo tdd_nl( 'statement_text' ); ?>><?php echo tdd_text( 'statement_text' ); ?></p>
        <a class="tag tag--lg" href="<?php echo $menu_url; ?>"<?php echo tdd_nl( 'menu_button' ); ?>><?php echo tdd_text( 'menu_button' ); ?></a>
      </div>
    </section>

<?php get_footer();

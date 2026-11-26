<?php
/**
 * Template Name: TDD - Menu
 * The menu items come from "Menu card" in the admin sidebar.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
$drinks     = tdd_menu_list( 'drinks' );
$sandwiches = tdd_menu_list( 'sandwiches', true );
$salads     = tdd_menu_list( 'salads', true );
$bakes      = tdd_menu_list( 'bakes' );
?>

    <!-- HERO -->
    <section class="page-hero page-hero--short">
      <div class="page-hero__bg media">
        <?php echo tdd_img( 'menu_bg', 'menu-bg.webp' ); ?>
      </div>
      <p class="corner corner--tl"><?php echo tdd_text( 'slogan' ); ?></p>
      <?php echo tdd_hours_corner(); ?>
      <h1 class="page-hero__title"><span class="line"><?php echo esc_html( tdd_get( 'menu_title' ) ); ?></span></h1>
    </section>

    <!-- Category tabs: sticky, jump to a section -->
    <nav class="carte-tabs" aria-label="Menu sections">
      <?php if ( $drinks ) : ?><a href="#drinks" class="is-active">Drinks</a><?php endif; ?>
      <?php if ( $sandwiches || $salads ) : ?><a href="#lunch">Lunch</a><?php endif; ?>
      <?php if ( $bakes ) : ?><a href="#bakes">Bakes</a><?php endif; ?>
    </nav>

    <?php if ( $drinks ) : ?>
    <section class="carte" id="drinks" data-logo="dark">
      <h2 class="carte__title">DRINKS</h2>
      <div class="carte__body">
        <?php echo $drinks; ?>
        <?php if ( tdd_get( 'drinks_note' ) ) : ?><p class="carte__note"><?php echo tdd_text( 'drinks_note' ); ?></p><?php endif; ?>
      </div>
    </section>
    <?php endif; ?>

    <?php if ( $sandwiches || $salads ) : ?>
    <section class="carte carte--yellow" id="lunch" data-logo="dark">
      <h2 class="carte__title">LUNCH</h2>
      <div class="carte__body">
        <div class="carte__cols">
          <?php if ( $sandwiches ) : ?><div><h3 class="carte__group">Sandwiches</h3><?php echo $sandwiches; ?></div><?php endif; ?>
          <?php if ( $salads ) : ?><div><h3 class="carte__group">Salads</h3><?php echo $salads; ?></div><?php endif; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <?php if ( $bakes ) : ?>
    <section class="carte" id="bakes" data-logo="dark">
      <h2 class="carte__title">BAKES</h2>
      <div class="carte__body">
        <?php echo $bakes; ?>
      </div>
    </section>
    <?php endif; ?>

<?php get_footer();

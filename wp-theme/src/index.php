<?php
/**
 * Fallback for any other page or post (e.g. privacy policy): simple text page in the site's style.
 * Home = front-page.php, Menu = template-menu.php, Corporate coffee = template-company-coffee.php, Corporate lunch = template-corporate-lunch.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

    <section class="plain" data-logo="dark">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article class="plain__inner">
          <h1 class="plain__title"><?php the_title(); ?></h1>
          <div class="plain__content"><?php the_content(); ?></div>
        </article>
      <?php endwhile; else : ?>
        <article class="plain__inner">
          <h1 class="plain__title">Not found</h1>
          <p><a class="tag tag--lg" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></p>
        </article>
      <?php endif; ?>
    </section>

<?php get_footer();

<?php
/**
 * Template Name: TDD - Company coffee
 * Form answers go to the address in Customize > The Daily Drip > Company coffee page.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
$btn = fn( $label ) => '<a href="#contact" class="tag">' . esc_html( $label ) . '</a>';
$sent = isset( $_GET['sent'] ) ? sanitize_key( $_GET['sent'] ) : '';
?>

    <!-- HERO - title over slow background -->
    <section class="page-hero">
      <div class="page-hero__bg media">
        <?php echo tdd_img( 'company_bg', 'company-bg.webp' ); ?>
      </div>
      <p class="corner corner--tl"<?php echo tdd_nl( 'company_label' ); ?>><?php echo tdd_text( 'company_label' ); ?></p>
      <p class="corner corner--tr"><?php echo tdd_text( 'slogan' ); ?></p>
      <h1 class="page-hero__title"><?php echo tdd_lines( 'company_title' ); ?></h1>
    </section>

    <!-- INTRO - words light up -->
    <section class="intro">
      <p class="label"<?php echo tdd_nl( 'company_label' ); ?>><?php echo tdd_text( 'company_label' ); ?></p>
      <p class="intro__text js-words"<?php echo tdd_nl( 'company_text', ' ' . $btn( tdd_get( 'company_button_nl' ) ?: tdd_get( 'company_button' ) ) ); ?>><?php echo tdd_text( 'company_text' ) . ' ' . $btn( tdd_get( 'company_button' ) ); ?></p>
    </section>

    <!-- CONTACT FORM - sent by WordPress to the "Form answers are sent to" address -->
    <section class="order" id="contact" data-logo="dark">
      <div class="order__intro">
        <h2<?php echo tdd_nl( 'form_title' ); ?>><?php echo tdd_text( 'form_title' ); ?></h2>
        <p<?php echo tdd_nl( 'form_text' ); ?>><?php echo tdd_text( 'form_text' ); ?></p>
      </div>
      <form class="form js-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" novalidate>
        <input type="hidden" name="action" value="tdd_contact">
        <input type="hidden" name="_t" value="<?php echo time(); ?>">
        <label><span data-nl="Naam">Name</span><input name="Name" required autocomplete="name"></label>
        <label><span data-nl="Bedrijf">Company</span><input name="Company" autocomplete="organization"></label>
        <label><span data-nl="E-mail">Email</span><input type="email" name="Email" required autocomplete="email"></label>
        <label><span data-nl="Telefoon">Phone</span><input type="tel" name="Phone" autocomplete="tel"></label>
        <label class="form__wide"><span data-nl="Bericht">Message</span><textarea name="Message" rows="4" required></textarea></label>
        <input type="text" name="_honey" class="form__honey" tabindex="-1" autocomplete="off" aria-hidden="true">
        <button class="tag tag--lg" type="submit" data-nl="Verstuur">Send</button>
        <p class="form__status form__wide" role="status" aria-live="polite"<?php echo '0' === $sent ? ' data-state="fail"' : ''; ?>></p>
      </form>
    </section>

<?php get_footer();

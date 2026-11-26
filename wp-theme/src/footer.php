<?php if ( ! defined( 'ABSPATH' ) ) exit;
$email     = tdd_get( 'email' );
$instagram = tdd_get( 'instagram' );
?>
    <footer class="footer" id="visit">
      <div class="footer__cols">
        <div><p class="label" data-nl="BEZOEK">VISIT</p><p><?php echo tdd_text( 'address' ); ?></p></div>
        <div><p class="label" data-nl="OPENINGSTIJDEN">HOURS</p><p<?php echo tdd_nl( 'hours' ); ?>><?php echo tdd_text( 'hours' ); ?></p></div>
        <div><p class="label">CONTACT</p><p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a><?php if ( $instagram ) : ?><br><a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?></p></div>
      </div>
      <img class="footer__logo" src="<?php echo esc_url( TDD_URI . '/img/logo.svg' ); ?>" alt="The Daily Drip">
    </footer>
  </main>

<?php wp_footer(); ?>
</body>
</html>

<?php
/**
 * After checkout billing form
 *
 * This template can be overridden by copying it to yourtheme/flexible-checkout-fields-pro/checkout/flexible-checkout-fields/after_checkout_billing_form.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$html_tag = $section_settings['section_title_type'];

?>
<div class="flexible-checkout-fields-after_checkout_billing_form <?php echo esc_attr( esc_attr( $section_settings['section_css'] ?? '' ) ); ?>">

	<?php if ( isset( $section_settings['section_title'] ) && $section_settings['section_title'] != '' ) : ?>
		<<?php echo esc_attr( $html_tag ); ?>>
			<?php echo wp_kses( $section_settings['section_title'], '' ); ?>
		</<?php echo esc_attr( $html_tag ); ?>>
	<?php endif; ?>

	<?php foreach ( $fields as $key => $field ) : ?>

		<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

	<?php endforeach; ?>

</div>

<?php
/**
 * Info
 *
 * This template can be overridden by copying it to yourtheme/flexible-checkout-fields-pro/fields/info.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$custom_attributes         = array();
if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
    foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
        $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
    }
}
?>
<p class="form-row form-row-wide form-info <?php echo esc_attr( implode( ' ', $args['class'] ) ); ?>"
	id="<?php echo esc_attr( $key ); ?>_field"
	data-priotiry="<?php echo esc_attr( $args['priority'] ); ?>"
	<?php echo empty( $custom_attributes ) ? '' : implode( ' ', $custom_attributes ); ?>
	>
	<?php echo wp_kses_post( $args['label'] ); ?>
</p>

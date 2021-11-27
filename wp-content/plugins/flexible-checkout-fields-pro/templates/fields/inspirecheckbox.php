<?php
/**
 * Checkbox
 *
 * This template can be overridden by copying it to yourtheme/flexible-checkout-fields-pro/fields/inspirecheckbox.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$required = '';
if ( $args['required'] == true ) {
    $required = '<abbr class="required" title="' . __( 'Required Field', 'flexible-checkout-fields-pro' ) . '">*</abbr>';
}
$checked = '';
if ( $value == $args['placeholder'] ) {
    $checked = " checked";
}

if ( empty( $args['placeholder'] ) ) {
    $args['placeholder'] = 1;
}
$custom_attributes         = array();
if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
    foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
        $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
    }
}
?>
<p class="form-row <?php echo esc_attr( implode( ' ', $args['class'] ) ); ?>"
   id="<?php echo esc_attr( $key ); ?>_field"
   data-priotiry="<?php echo esc_attr( $args['priority'] ); ?>">
    <label for="<?php echo esc_attr( $key ); ?>">
		<input
			type="checkbox"
			class="input-checkbox input-inspirecheckbox"
			name="<?php echo esc_attr( $key ); ?>"
			id="<?php echo esc_attr( $key ); ?>"
			value="<?php echo esc_attr( $args['placeholder'] ); ?>"
			<?php echo $checked; ?>
			<?php echo empty( $custom_attributes ) ? '' : implode( ' ', $custom_attributes ); ?>
		/>
		<?php echo wp_kses( $args['label'], '' ); ?> <?php echo $required; ?>
	</label>
</p>

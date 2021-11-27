<?php
/**
 * Datepicker
 *
 * This template can be overridden by copying it to yourtheme/flexible-checkout-fields-pro/fields/datepicker.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$required = '';
if($args['required'] == true){
    $required = '<abbr class="required" title="'. __( 'Required Field', 'flexible-checkout-fields-pro' ).'">*</abbr>';
}
$custom_attributes         = array();
if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
    foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
        $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
    }
}
?>
<p class="form-row form-datepicker <?php echo esc_attr( implode( ' ', $args['class'] ) ); ?>"
   id="<?php echo esc_attr( $key ) ?>_field"
   data-priotiry="<?php echo esc_attr( $args['priority'] ); ?>">
	<label for="<?php echo esc_attr( $key ); ?>">
		<?php echo wp_kses( $args['label'], '' ); ?> <?php echo $required; ?>
	</label>
	<input type="text"
        class="input-text load-datepicker"
        name="<?php echo esc_attr( $key ); ?>"
        id="<?php echo esc_attr( $key ); ?>"
	    placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
        value="<?php echo esc_attr( $value ); ?>"
		date_format="<?php echo esc_attr( $args['custom_attributes']['date_format'] ); ?>"
        days_before="<?php echo ( isset( $args['custom_attributes']['days_before'] ) ) ? esc_attr( $args['custom_attributes']['days_before'] ) : ''; ?>"
        days_after="<?php echo ( isset( $args['custom_attributes']['days_after'] ) ) ? esc_attr( $args['custom_attributes']['days_after'] ) : ''; ?>"
        <?php echo empty( $custom_attributes ) ? '' : implode( ' ', $custom_attributes ); ?>
	/>
</p>

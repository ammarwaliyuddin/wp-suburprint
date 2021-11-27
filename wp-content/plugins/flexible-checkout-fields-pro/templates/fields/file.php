<?php
/**
 * File
 *
 * This template can be overridden by copying it to yourtheme/flexible-checkout-fields-pro/fields/file.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$required = '';
if($args['required'] == true){
	$required = '<abbr class="required" title="'. __( 'Required Field', 'flexible-checkout-fields-pro' ).'">*</abbr>';
	$mandatory_field = "<strong>{$args['label']}</strong>";
}
$custom_attributes         = array();
if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
	foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
		$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
	}
}
?>
<p class="form-row form-file <?php echo esc_attr( implode( ' ', $args['class'] ) ) ?>" id="<?php echo esc_attr( $key ); ?>_field" data-priotiry="<?php echo esc_attr( $args['priority'] ); ?>">
	<label for="<?php echo esc_attr( $key ); ?>"><?php echo wp_kses( $args['label'], '' ); ?> <?php echo $required; ?></label>
	<input type="text" style="display:none;" class="inspire-file" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
	<input type="file" field_name="<?php echo esc_attr( $key ); ?>" style="display:none;" class="inspire-file-file" name="<?php echo esc_attr( $key ); ?>_file" id="<?php echo esc_attr( $key ); ?>_file" value="<?php echo esc_attr( $value ); ?>"
	<?php echo empty( $custom_attributes ) ? '' : implode( ' ', $custom_attributes ); ?>
	'/>
	<span class="inspire-file-info" style="display:none;"><br/></span>
	<span class="inspire-file-error" style="display:none;"><br/></span>
	<input class="inspire-file-add-button" type="button" value="<?php echo __( 'Upload File', 'flexible-checkout-fields-pro' ); ?>" />
	<input class="inspire-file-delete-button" type="button" value="<?php echo __( 'Remove File', 'flexible-checkout-fields-pro' ); ?>" style="display:none;" />
</p>

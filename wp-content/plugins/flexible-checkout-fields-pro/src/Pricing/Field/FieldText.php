<?php
/**
 * Supports "Text" field type for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Field;

use WPDesk\FCF\Pro\Pricing\Field\FieldAbstract;
use WPDesk\FCF\Pro\Pricing\Field\FieldInterface;
use WPDesk\FCF\Pro\Pricing\Fields;

/**
 * FieldText class for Pricing.
 */
class FieldText extends FieldAbstract implements FieldInterface {

	/**
	 * Returns available options of price for field.
	 *
	 * @return array List of options.
	 */
	public function get_options_for_price_values() {
		return [
			'_value' => $this->get_field_label(),
		];
	}

	/**
	 * Returns field label for WooCommerce Fees.
	 *
	 * @param string|array $field_value Posted field value.
	 * @param string       $default_value Default field value.
	 *
	 * @return string Label of field with optionally field value.
	 */
	public function get_field_label_for_fee( $field_value, string $default_value ) {
		return $this->get_field_label();
	}

	/**
	 * Adds information about price in field args.
	 *
	 * @param array  $args Settings of field.
	 * @param string $value Value of field.
	 * @param string $label Label of field.
	 *
	 * @return array Data of field.
	 */
	public function add_price_in_field_label( array $args, string $value, string $label ) {
		$args['label'] .= $label;
		return $args;
	}

	/**
	 * Checks if current field value matches required value.
	 *
	 * @param string       $option_value Required field value.
	 * @param string|array $current_value Current value of field.
	 *
	 * @return bool Status of field value.
	 */
	public function is_valid_field_value( string $option_value, $current_value ) {
		return ( ( $option_value === '_value' ) & ! empty( $current_value ) );
	}
}

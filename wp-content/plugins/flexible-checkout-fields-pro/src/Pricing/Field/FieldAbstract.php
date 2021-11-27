<?php
/**
 * Support fields type for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Field;

use FCFProVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FCFProVendor\WPDesk\View\Resolver\DirResolver;
use WPDesk\FCF\Pro\Pricing\Field\FieldInterface;

/**
 * FieldAbstract class for Pricing.
 */
abstract class FieldAbstract implements FieldInterface {

	const OPTION_PRICING_ENABLED = 'pricing_enabled';
	const OPTION_PRICING_VALUES  = 'pricing_values';

	/**
	 * Settings of field.
	 *
	 * @var array
	 */
	private $field_data = [];

	/**
	 * Class constructor.
	 *
	 * @param array $field_data Settings of field.
	 */
	public function __construct( array $field_data ) {
		$this->field_data = $field_data;
	}

	/**
	 * Checks if pricing is enabled for field.
	 *
	 * @return bool Status of pricing for field.
	 */
	public function is_pricing_enabled() {
		return ( isset( $this->field_data[ self::OPTION_PRICING_ENABLED ] ) && $this->field_data[ self::OPTION_PRICING_ENABLED ] );
	}

	/**
	 * Returns field name.
	 *
	 * @return string Key of field.
	 */
	public function get_field_name() {
		return $this->field_data['name'];
	}

	/**
	 * Returns field label.
	 *
	 * @return string Label of field.
	 */
	public function get_field_label() {
		return $this->field_data['label'];
	}

	/**
	 * Returns options for field.
	 *
	 * @return array List of options (array keys are values, array values are labels).
	 */
	public function get_field_options() {
		$field_options = new \Flexible_Checkout_Fields_Field_Options( $this->field_data['option'] );
		return $field_options->get_options_as_array();
	}

	/**
	 * Returns pricing types for field.
	 *
	 * @return array Types of pricing.
	 */
	public function get_field_pricing_types() {
		$types = $this->field_data[ self::OPTION_PRICING_VALUES ] ?? [];
		foreach ( $types as $value => $type ) {
			if ( empty( $type['value'] ) ) {
				unset( $types[ $value ] );
			}
		}
		return $types;
	}

	/**
	 * Returns field label for field settings.
	 *
	 * @return string Format for sprintf() function.
	 */
	public function get_field_label_for_settings() {
		/* translators: %s: field label */
		return __( 'Pricing options for %s', 'flexible-checkout-fields-pro' );
	}
}

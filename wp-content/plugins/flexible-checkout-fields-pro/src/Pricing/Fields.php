<?php
/**
 * Support fields types for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing;

use FCFProVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FCF\Pro\Plugin\Settings as PluginSettings;
use WPDesk\FCF\Pro\Pricing\Field\FieldInterface;
use WPDesk\FCF\Pro\Pricing\Field\FieldIntegration;
use WPDesk\FCF\Pro\Pricing\Field\FieldText;
use WPDesk\FCF\Pro\Pricing\Field\FieldRadio;
use WPDesk\FCF\Pro\Pricing\Field\FieldMultiselect;

/**
 * Fields class for Pricing.
 */
class Fields implements Hookable {

	const SUPPORTED_FIELD_TYPES = [
		'text',
		'textarea',
		'inspirecheckbox',
		'inspireradio',
		'select',
		'wpdeskmultiselect',
		'datepicker',
		'timepicker',
		'colorpicker',
		'file',
	];

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'init_pricing_for_fields' ] );
	}

	/**
	 * Initiates loading of pricing handling for fields with enabled option.
	 *
	 * @internal
	 */
	public function init_pricing_for_fields() {
		$sections = ( new PluginSettings() )->get_settings_for_fields();
		if ( ! $sections || ! is_array( $sections ) ) {
			return;
		}

		foreach ( $sections as $fields ) {
			if ( ! $fields ) {
				continue;
			}
			foreach ( $fields as $field_key => $field ) {
				if ( isset( $field['custom_field'] ) && $field['custom_field'] ) {
					$this->init_pricing_for_field( $field );
				}
			}
		}
	}

	/**
	 * Initiates loading of pricing handling for field.
	 *
	 * @param array $field Settings of field.
	 */
	private function init_pricing_for_field( array $field ) {
		$field_object = $this->create_field( $field );
		if ( $field_object === null ) {
			return;
		}

		( new FieldIntegration( $field_object ) )->hooks();
		( new Types( $field_object ) )->load_pricing_types();
	}

	/**
	 * Returns class object for handling of field type.
	 *
	 * @param array $field Settings of field.
	 *
	 * @return FieldInterface|null Class object of field type, if is available.
	 */
	private function create_field( array $field ) {
		switch ( $field['type'] ?? 'text' ) {
			case 'text':
			case 'textarea':
			case 'inspirecheckbox':
			case 'datepicker':
			case 'timepicker':
			case 'colorpicker':
			case 'file':
				return new FieldText( $field );
			case 'inspireradio':
			case 'select':
				return new FieldRadio( $field );
			case 'wpdeskmultiselect':
				return new FieldMultiselect( $field );
		}
		return null;
	}
}

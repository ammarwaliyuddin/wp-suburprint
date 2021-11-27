<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Tab\AdvancedTab;

/**
 * Supports option settings for field.
 */
class FileSizeOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'file_size';

	/**
	 * Returns name of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_name(): string {
		return self::FIELD_NAME;
	}

	/**
	 * Returns name of option tab.
	 *
	 * @return string Tab name.
	 */
	public function get_option_tab(): string {
		return AdvancedTab::TAB_NAME;
	}

	/**
	 * Returns type of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_type(): string {
		return self::FIELD_TYPE_NUMBER;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Maximum file size in MB', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns list of HTML attributes for field with their values.
	 *
	 * @return array Atts for field.
	 */
	public function get_input_atts(): array {
		return [
			'min' => '0',
		];
	}

	/**
	 * Filters option value from all unsafe strings.
	 *
	 * @param string|array $field_value Original option value.
	 *
	 * @return string|array Updated value of option.
	 */
	public function sanitize_option_value( $field_value ) {
		if ( ! $field_value ) {
			return '';
		}
		return intval( $field_value );
	}
}

<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Pro\Settings\Option\SettingSectionsSectionOption;

/**
 * Supports option settings for field.
 */
class SettingSectionsOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'settings_sections';

	/**
	 * Returns name of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_name(): string {
		return self::FIELD_NAME;
	}

	/**
	 * Returns type of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_type(): string {
		return self::FIELD_TYPE_CHECKBOX_LIST;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Custom Sections', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_label_tooltip(): string {
		return __( 'Selected sections appear as tabs in the plugin menu.', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns subfields of option, if exists.
	 *
	 * @return OptionInterface[] List of option children.
	 */
	public function get_children(): array {
		$sections = apply_filters( 'flexible_checkout_fields_all_sections', [] );
		$objects  = [];
		foreach ( $sections as $section ) {
			if ( ! ( $section['custom_section'] ?? false ) ) {
				continue;
			}
			$objects[] = new SettingSectionsSectionOption(
				$section['section'],
				$section['title']
			);
		}
		return $objects;
	}
}

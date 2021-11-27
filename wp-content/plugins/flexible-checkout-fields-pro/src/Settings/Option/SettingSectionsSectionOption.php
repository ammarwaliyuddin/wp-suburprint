<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;

/**
 * Supports option settings for field.
 */
class SettingSectionsSectionOption extends OptionAbstract implements OptionInterface {

	/**
	 * Name of option.
	 *
	 * @var string
	 */
	private $section_name = '';

	/**
	 * Label of option.
	 *
	 * @var string
	 */
	private $section_label = '';

	/**
	 * Class constructor.
	 *
	 * @param string $section_name Name of option.
	 * @param string $section_label Label of option.
	 */
	public function __construct( string $section_name, string $section_label ) {
		$this->section_name  = $section_name;
		$this->section_label = $section_label;
	}

	/**
	 * Returns name of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_name(): string {
		return 'inspire_checkout_fields_' . $this->section_name;
	}

	/**
	 * Returns type of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_type(): string {
		return self::FIELD_TYPE_CHECKBOX;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return sprintf( '%s (%s)', $this->section_label, $this->section_name );
	}

	/**
	 * Returns default value of option.
	 *
	 * @return string|array Default value.
	 */
	public function get_default_value() {
		return '0';
	}
}

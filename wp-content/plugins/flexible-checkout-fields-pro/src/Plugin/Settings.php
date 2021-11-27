<?php
/**
 * Stores settings for plugin.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Plugin;

/**
 * Settings class.
 */
class Settings {

	const SETTINGS_OPTION_KEY = 'inspire_checkout_fields_settings';

	/**
	 * Saved field settings.
	 *
	 * @var array
	 */
	private $settings_cached = null;

	/**
	 * Returns settings for fields.
	 *
	 * @return array Data of fields settings.
	 */
	public function get_settings_for_fields() {
		if ( $this->settings_cached === null ) {
			$this->settings_cached = get_option( self::SETTINGS_OPTION_KEY, [] );
		}
		return $this->settings_cached;
	}

	/**
	 * Returns settings for single field.
	 *
	 * @param string $field_name Name of checkout field.
	 *
	 * @return array Data of field settings.
	 */
	public function get_settings_for_field( $field_name ) {
		foreach ( $this->get_settings_for_fields() as $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( $key === $field_name ) {
					return $field;
				}
			}
		}
		return [];
	}
}

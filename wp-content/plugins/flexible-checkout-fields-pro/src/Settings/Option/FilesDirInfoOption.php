<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Tab\GeneralTab;

/**
 * Supports option settings for field.
 */
class FilesDirInfoOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'file_dir_info';

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
		return GeneralTab::TAB_NAME;
	}

	/**
	 * Returns type of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_type(): string {
		return self::FIELD_TYPE_INFO;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		$upload_dir = new \Flexible_Checkout_Fields_Pro_File_Upload_Dir();
		$upload_dir->add_filter();
		$wp_upload_dir = wp_upload_dir();
		$upload_dir->remove_filter();
		$upload_folder = substr( $wp_upload_dir['path'], strlen( ABSPATH ) );

		return sprintf(
			/* translators: %s: uploads path */
			__( 'Files will be saved to: %s. The file will not be an attachment to the confirmation email.', 'flexible-checkout-fields-pro' ),
			sprintf( '<strong>%s</strong>', $upload_folder )
		);
	}
}

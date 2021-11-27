<?php
/**
 * .
 *
 * @package WPDesk\FCF\Free
 */

namespace WPDesk\FCF\Pro\Settings;

use WPDesk\FCF\Free\Settings\Form\FormIntegration;
use WPDesk\FCF\Pro\Settings\Form\EditSectionForm;
use WPDesk\FCF\Pro\Settings\Form\SettingsPageForm;

/**
 * Supports management for forms.
 */
class Forms {

	/**
	 * Initializes actions for class.
	 *
	 * @return void
	 */
	public function init() {
		( new FormIntegration( new EditSectionForm() ) )->hooks();
		( new FormIntegration( new SettingsPageForm() ) )->hooks();
	}
}

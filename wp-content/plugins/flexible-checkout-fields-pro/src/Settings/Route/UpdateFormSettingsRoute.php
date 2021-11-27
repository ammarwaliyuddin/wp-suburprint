<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Route;

use WPDesk\FCF\Free\Settings\Route\UpdateFormSettingsRoute as DefaultUpdateFormSettingsRoute;
use WPDesk\FCF\Free\Settings\Route\RouteInterface;
use WPDesk\FCF\Pro\Settings\Form\SettingsPageForm;

/**
 * Supports settings for REST API route.
 */
class UpdateFormSettingsRoute extends DefaultUpdateFormSettingsRoute implements RouteInterface {

	/**
	 * Returns data to be returned for endpoint.
	 *
	 * @param array $params Params for endpoint.
	 *
	 * @return mixed Response data.
	 *
	 * @throws \Exception .
	 */
	public function get_endpoint_response( array $params ) {
		try {
			$status = ( new SettingsPageForm() )->save_form_data( $params );
			if ( $status !== true ) {
				throw new \Exception();
			}

			return null;
		} catch ( \Exception $e ) {
			throw $e;
		}
	}
}

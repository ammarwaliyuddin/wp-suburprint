<?php
/**
 * .
 *
 * @package WPDesk\FCF\Free
 */

namespace WPDesk\FCF\Pro\Settings;

use WPDesk\FCF\Free\Settings\Route\RouteIntegration;
use WPDesk\FCF\Pro\Settings\Route\FieldsFieldRoute;
use WPDesk\FCF\Pro\Settings\Route\FieldsConditionRoute;
use WPDesk\FCF\Pro\Settings\Route\FieldsValueRoute;
use WPDesk\FCF\Pro\Settings\Route\ProductsRoute;
use WPDesk\FCF\Pro\Settings\Route\ProductsCatsRoute;
use WPDesk\FCF\Pro\Settings\Route\ShippingZonesRoute;
use WPDesk\FCF\Pro\Settings\Route\ShippingMethodsRoute;
use WPDesk\FCF\Pro\Settings\Route\UpdateFormSectionRoute;
use WPDesk\FCF\Pro\Settings\Route\UpdateFormSettingsRoute;

/**
 * Supports management for REST API routes.
 */
class Routes {

	/**
	 * Initializes actions for class.
	 *
	 * @return void
	 */
	public function init() {
		( new RouteIntegration( new FieldsFieldRoute() ) )->hooks();
		( new RouteIntegration( new FieldsConditionRoute() ) )->hooks();
		( new RouteIntegration( new FieldsValueRoute() ) )->hooks();
		( new RouteIntegration( new ProductsRoute() ) )->hooks();
		( new RouteIntegration( new ProductsCatsRoute() ) )->hooks();
		( new RouteIntegration( new ShippingZonesRoute() ) )->hooks();
		( new RouteIntegration( new ShippingMethodsRoute() ) )->hooks();
		( new RouteIntegration( new UpdateFormSectionRoute() ) )->hooks();
		( new RouteIntegration( new UpdateFormSettingsRoute() ) )->hooks();
	}
}

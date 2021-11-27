<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Route;

use WPDesk\FCF\Free\Settings\Route\RouteAbstract;
use WPDesk\FCF\Free\Settings\Route\RouteInterface;

/**
 * Supports settings for REST API route.
 */
class ShippingZonesRoute extends RouteAbstract implements RouteInterface {

	const REST_API_ROUTE   = 'shipping-zones';
	const ZONE_DEFAULT_KEY = 'no_shipping_zones';

	/**
	 * Returns route of REST API endpoint.
	 *
	 * @return string Route name.
	 */
	public function get_endpoint_route(): string {
		return self::REST_API_ROUTE;
	}

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
		$zones = \WC_Shipping_Zones::get_zones();

		$values = [
			self::ZONE_DEFAULT_KEY => __( 'No Shipping Zones or Global Methods', 'flexible-checkout-fields-pro' ),
		];
		foreach ( $zones as $zone ) {
			$values[ $zone['zone_id'] ] = $zone['zone_name'];
		}
		return $values;
	}
}

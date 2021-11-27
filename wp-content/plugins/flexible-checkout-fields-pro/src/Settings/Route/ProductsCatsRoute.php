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
class ProductsCatsRoute extends RouteAbstract implements RouteInterface {

	const REST_API_ROUTE = 'products-cats';

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
		$wpml_integration = $this->get_integration_wpml();
		if ( $wpml_integration ) {
			remove_filter( 'get_terms_args', [ $wpml_integration, 'get_terms_args_filter' ] );
			remove_filter( 'get_term', [ $wpml_integration, 'get_term_adjust_id' ] );
			remove_filter( 'terms_clauses', [ $wpml_integration, 'terms_clauses' ] );
			remove_filter( 'get_term', [ $wpml_integration, 'get_term_adjust_id' ], 1, 1 );
		}

		$cats = get_terms(
			[
				'taxonomy'   => 'product_cat',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			]
		);

		if ( $wpml_integration ) {
			add_filter( 'terms_clauses', [ $wpml_integration, 'terms_clauses' ] );
			add_filter( 'get_term', [ $wpml_integration, 'get_term_adjust_id' ] );
			add_filter( 'get_terms_args', [ $wpml_integration, 'get_terms_args_filter' ] );
			add_filter( 'get_term', [ $wpml_integration, 'get_term_adjust_id' ], 1, 1 );
		}

		$values = [];
		foreach ( $cats as $cat ) {
			$values[ $cat->term_id ] = sprintf( '%s (#%d)', $cat->name, $cat->term_id );
		}
		return $values;
	}

	/**
	 * Returns integration object with WPML plugin.
	 *
	 * @return object|null Main SitePress Class.
	 */
	private function get_integration_wpml() {
		global $sitepress;
		if ( ! $sitepress || ! is_object( $sitepress )
			|| ! method_exists( $sitepress, 'get_terms_args_filter' )
			|| ! method_exists( $sitepress, 'get_term_adjust_id' )
			|| ! method_exists( $sitepress, 'terms_clauses' )
			|| ! method_exists( $sitepress, 'get_term_adjust_id' ) ) {
			return null;
		}
		return $sitepress;
	}
}

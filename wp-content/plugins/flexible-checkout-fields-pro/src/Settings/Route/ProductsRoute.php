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
class ProductsRoute extends RouteAbstract implements RouteInterface {

	const REST_API_ROUTE = 'products';

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
		$posts = get_posts(
			[
				'posts_per_page' => -1,
				'post_type'      => [ 'product', 'product_variation' ],
				'orderby'        => 'title',
				'order'          => 'ASC',
				'lang'           => '',
			]
		);

		$values = [];
		foreach ( $posts as $post ) {
			$values[ $post->ID ] = sprintf( '%s (#%d)', $post->post_title, $post->ID );
		}
		return $values;
	}
}

<?php
/**
 * Support fields integration for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Field;

use WPDesk\FCF\Pro\Pricing\Field\FieldInterface;

/**
 * FieldIntegration class for Pricing.
 */
class FieldIntegration {

	/**
	 * Class object for field type.
	 *
	 * @var FieldInterface
	 */
	private $field_object;

	/**
	 * Class constructor.
	 *
	 * @param FieldInterface $field_object Class object of field type.
	 */
	public function __construct( FieldInterface $field_object ) {
		$this->field_object = $field_object;
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'wp_footer', [ $this, 'add_scripts_in_footer' ] );
	}

	/**
	 * Adds scripts in footer to trigger update_checkout after field change.
	 *
	 * @internal
	 */
	public function add_scripts_in_footer() {
		if ( ! is_checkout() || ! $this->field_object->is_pricing_enabled() ) {
			return;
		}

		?>
		<script>
			jQuery(function($) {
				jQuery('[name^="<?php echo esc_html( $this->field_object->get_field_name() ); ?>"]').change(function() {
					$('body').trigger('update_checkout');
				});
			});
		</script>
		<?php
	}
}

<?php

use WPDesk\FCF\Pro\Plugin as PluginPro;

/**
 * Class Flexible_Checkout_Fields_Pro_Plugin
 */
class Flexible_Checkout_Fields_Pro_Plugin
	extends \FCFProVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin
	implements \FCFProVendor\WPDesk\PluginBuilder\Plugin\HookableCollection {

	use \FCFProVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
	use \FCFProVendor\WPDesk\PluginBuilder\Plugin\TemplateLoad;

	/**
	 * Scripts version.
	 *
	 * @var string
	 */
	private $script_version = FLEXIBLE_CHECKOUT_FIELDS_PRO_VERSION . '.' . '44';

	/**
	 * PRO Types.
	 *
	 * @var Flexible_Checkout_Fields_Pro_Types
	 */
	public $pro_types;

	/**
	 * Renderer.
	 *
	 * @var FCFProVendor\WPDesk\View\Renderer\Renderer;
	 */
	private $renderer;

	/**
	 * Flexible_Checkout_Fields_Plugin
	 *
	 * @var Flexible_Checkout_Fields_Plugin
	 */
	private $flexible_checkout_fields_plugin;

	/**
	 * Select field type.
	 *
	 * @var Flexible_Checkout_Fields_Pro_Select_Field_Type
	 */
	private $select_field_type;

	/**
	 * Multi Select field type.
	 *
	 * @var Flexible_Checkout_Fields_Pro_Multi_Select_Field_Type
	 */
	private $multi_select_field_type;

	/**
	 * Radio field type.
	 *
	 * @var Flexible_Checkout_Fields_Pro_Inspire_Radio_Field_Type
	 */
	private $radio_field_type;

	/**
	 * File field type.
	 *
	 * @var Flexible_Checkout_Fields_Pro_File_Field_Type
	 */
	private $file_field_type;

	/**
	 * HTML field type.
	 *
	 * @var Flexible_Checkout_Fields_Pro_HTML_Field_Type
	 */
	private $html_field_type;

	/**
	 * Instance of new version main class of plugin.
	 *
	 * @var PluginPro
	 */
	private $plugin_pro;


	/**
	 * Flexible_Checkout_Fields_Pro_Plugin constructor.
	 *
	 * @param FCFProVendor\WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( FCFProVendor\WPDesk_Plugin_Info $plugin_info ) {
		parent::__construct( $plugin_info );
		$this->plugin_pro = new PluginPro( $plugin_info, $this );
	}

	/**
	 * Get select_field_type.
	 *
	 * @return Flexible_Checkout_Fields_Pro_Select_Field_Type
	 */
	public function get_select_field_type() {
		return $this->select_field_type;
	}

	/**
	 * Get multi_select_field_type.
	 *
	 * @return Flexible_Checkout_Fields_Pro_Multi_Select_Field_Type
	 */
	public function get_multi_select_field_type() {
		return $this->multi_select_field_type;
	}

	/**
	 * Get radio_field_type.
	 *
	 * @return Flexible_Checkout_Fields_Pro_Inspire_Radio_Field_Type
	 */
	public function get_radio_field_type() {
		return $this->radio_field_type;
	}

	/**
	 * Get file_field_type.
	 *
	 * @return Flexible_Checkout_Fields_Pro_File_Field_Type
	 */
	public function get_file_field_type() {
		return $this->file_field_type;
	}



	/**
	 * Get flexible checkout fields plugin.
	 *
	 * @return Flexible_Checkout_Fields_Plugin
	 */
	public function get_flexible_checkout_fields_plugin() {
		if ( empty( $this->flexible_checkout_fields_plugin ) ) {
			$this->flexible_checkout_fields_plugin = flexible_checkout_fields();
		}
		return $this->flexible_checkout_fields_plugin;
	}

	/**
	 * Get script version.
	 *
	 * @return string;
	 */
	public function get_script_version() {
		return $this->script_version;
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		$this->plugin_pro->hooks();
		parent::hooks();
		$this->hooks_on_hookable_objects();
	}

	/**
	 * Init base variables for plugin
	 */
	public function init_base_variables() {
		$this->plugin_url = $this->plugin_info->get_plugin_url();

		$this->plugin_path   = $this->plugin_info->get_plugin_dir();
		$this->template_path = $this->plugin_info->get_text_domain();

		$this->plugin_namespace = $this->plugin_info->get_text_domain();
		$this->template_path    = $this->plugin_info->get_text_domain();


	}

	/**
	 * Set renderer.
	 */
	private function init_renderer() {
		$resolver = new \FCFProVendor\WPDesk\View\Resolver\ChainResolver();
		$resolver->appendResolver( new \FCFProVendor\WPDesk\View\Resolver\WPThemeResolver( $this->get_template_path() ) );
		$resolver->appendResolver( new \FCFProVendor\WPDesk\View\Resolver\DirResolver( trailingslashit( $this->plugin_path ) . 'templates' ) );
		$this->renderer = new FCFProVendor\WPDesk\View\Renderer\SimplePhpRenderer( $resolver );
	}

	/**
	 * Initializes plugin functionality.
	 */
	public function init() {
		$this->plugin_pro->load_action_init();
	}

	/**
	 * Initializes plugin functionality after "flexible_checkout_fields/init" action.
	 */
	public function load_after_action_init() {
		$this->init_base_variables();

		$this->init_renderer();

		$checkout_fields_pro = new Flexible_Checkout_Fields_Pro( $this );
		$this->add_hookable( $checkout_fields_pro );

		$this->add_hookable( new Flexible_Checkout_Fields_Conditional_Logic_Checkout( $this ) );

		$this->add_hookable( new Flexible_Checkout_Fields_Conditional_Logic_Order( $this ) );

		$this->select_field_type = new Flexible_Checkout_Fields_Pro_Select_Field_Type( $checkout_fields_pro, $this->renderer );
		$this->add_hookable( $this->select_field_type );

		$this->html_field_type = new Flexible_Checkout_Fields_Pro_HTML_Field_Type( $checkout_fields_pro, $this->renderer );
		$this->add_hookable( $this->html_field_type );

		$this->multi_select_field_type = new Flexible_Checkout_Fields_Pro_Multi_Select_Field_Type( $checkout_fields_pro, $this->renderer );
		$this->add_hookable( $this->multi_select_field_type );
		$this->radio_field_type = new Flexible_Checkout_Fields_Pro_Inspire_Radio_Field_Type( $checkout_fields_pro, $this->renderer );
		$this->add_hookable( $this->radio_field_type );

		$this->file_field_type = new Flexible_Checkout_Fields_Pro_File_Field_Type( $checkout_fields_pro, $this->renderer );
		$this->add_hookable( $this->file_field_type );
		$this->add_hookable( new Flexible_Checkout_Fields_Pro_File_Field_Downloader() );
		$this->add_hookable( new Flexible_Checkout_Fields_Pro_File_Field_Ajax( $checkout_fields_pro ) );
		$this->add_hookable( new Flexible_Checkout_Fields_Pro_File_Field_Order_Metabox( $checkout_fields_pro ) );

		$this->pro_types = new Flexible_Checkout_Fields_Pro_Types( $this );
		$this->add_hookable( $this->pro_types );

		$this->add_hookable( new Flexible_Checkout_Fields_Order_Metabox( $checkout_fields_pro, $this ) );

		$this->add_hookable( new Flexible_Checkout_Fields_Pro_Datepicker_Validation() );

		$this->plugin_pro->init();
		parent::init();
	}

	/**
	 * Links filter.
	 *
	 * @param array $links Links.
	 * @return array
	 */
	public function links_filter( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=inspire_checkout_fields_settings' ) . '">' . __( 'Settings', 'flexible-checkout-fields-pro' ) . '</a>',
			'<a href="' . esc_url( apply_filters( 'flexible_checkout_fields/short_url', 'https://wpde.sk/fcf-settings-row-action-docs', 'fcf-settings-row-action-docs' ) ) . '" target="_blank">' . __( 'Docs', 'flexible-checkout-fields-pro' ) . '</a>',
			'<a href="' . esc_url( apply_filters( 'flexible_checkout_fields/short_url', 'https://wpde.sk/fcf-settings-row-action-support-pro', 'fcf-settings-row-action-support-pro' ) ) . '" target="_blank">' . __( 'Support', 'flexible-checkout-fields-pro' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Renders end returns selected template
	 *
	 * @param string $name Name of the template.
	 * @param string $path Additional inner path to the template.
	 * @param array  $args args Accessible from template.
	 *
	 * @return string
	 */
	public function load_template( $name, $path = '', $args = array() ) {
		if ( '' !== $path ) {
			$template = trailingslashit( $path ) . $name;
		} else {
			$template = $name;
		}
		return $this->renderer->render( $template, $args );
	}

	/**
	 * Should enqueue admin scripts.
	 * Script should be loaded on FCF Settings and order edit.
	 *
	 * @return bool
	 */
	private function should_enqueue_admin_scripts() {
		$current_screen = get_current_screen();
		if ( isset( $current_screen )
			&& ( in_array( $current_screen->id, array(
				'shop_order',
				'shop_subscription',
			), true ) )
		) {
			return true;
		}
		return false;
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_enqueue_scripts() {
		$current_screen = get_current_screen();
		if ( $this->should_enqueue_admin_scripts() ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'inspire_checkout_fields_colorpicker_style',
				trailingslashit( $this->get_plugin_assets_url() ) . 'css/colorpicker' . $suffix . '.css',
				array(),
				$this->script_version
			);
			wp_enqueue_style( 'inspire_checkout_fields_timepicker_style',
				trailingslashit( $this->get_plugin_assets_url() ) . 'css/jquery.timeselector' . $suffix . '.css',
				array(),
				$this->script_version
			);

			wp_enqueue_script( 'inspire_checkout_fields_colorpicker_js',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/colorpicker' . $suffix . '.js',
				array( 'jquery' ),
				$this->script_version
			);
			wp_enqueue_script( 'inspire_checkout_fields_timepicker_js',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/jquery.timeselector' . $suffix . '.js',
				array( 'jquery' ), $this->script_version
			);

			wp_enqueue_style( 'inspire_checkout_fields_pro_admin_style',
				trailingslashit( $this->get_plugin_assets_url() ) . 'css/admin' . $suffix . '.css',
				array(), $this->script_version
			);
			wp_enqueue_script( 'inspire_checkout_fields_pro_admin_pro_js',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/admin' . $suffix . '.js',
				array( 'jquery' ), $this->script_version, true
			);
		}
	}

	/**
	 * Enqueue scripts.
	 */
	public function wp_enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( is_checkout() || is_account_page() ) {
			wp_enqueue_script( 'inspire_checkout_fields_front_pro_js',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/front' . $suffix . '.js',
				array( 'jquery' ), $this->script_version
			);
			wp_enqueue_style( 'inspire_checkout_fields_colorpicker_style',
				trailingslashit( $this->get_plugin_assets_url() ) . 'css/colorpicker' . $suffix . '.css',
				array(), $this->script_version
			);
			wp_enqueue_style( 'inspire_checkout_fields_timepicker_style',
				trailingslashit( $this->get_plugin_assets_url() ) . 'css/jquery.timeselector' . $suffix . '.css',
				array(), $this->script_version
			);

			wp_enqueue_script( 'inspire_checkout_fields_colorpicker_js',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/colorpicker' . $suffix . '.js',
				array( 'jquery' ), $this->script_version
			);
			wp_enqueue_script( 'inspire_checkout_fields_timepicker_js',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/jquery.timeselector' . $suffix . '.js',
				array( 'jquery' ), $this->script_version
			);
		}
		if ( is_checkout() ) {
			wp_enqueue_script( 'inspire_checkout_fields_pro_checkout_js',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/checkout' . $suffix . '.js',
				array( 'jquery' ), $this->script_version, true
			);
			wp_enqueue_script( 'inspire_checkout_fields_pro_shipping_checkout_js',
				trailingslashit( $this->get_plugin_assets_url() ) . 'js/checkout_shipping_conditions' . $suffix . '.js',
				array( 'jquery' ), $this->script_version, true
			);
		}

	}

}

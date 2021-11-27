<?php

namespace FCFProVendor\WPDesk\Helper\Integration;

use FCFProVendor\WPDesk\Helper\Page\SettingsPage;
use FCFProVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FCFProVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FCFProVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
/**
 * Integrates WP Desk main settings page with WordPress
 *
 * @package WPDesk\Helper
 */
class SettingsIntegration implements \FCFProVendor\WPDesk\PluginBuilder\Plugin\Hookable, \FCFProVendor\WPDesk\PluginBuilder\Plugin\HookableCollection
{
    use HookableParent;
    /** @var SettingsPage */
    private $settings_page;
    public function __construct(\FCFProVendor\WPDesk\Helper\Page\SettingsPage $settingsPage)
    {
        $this->add_hookable($settingsPage);
    }
    /**
     * @return void
     */
    public function hooks()
    {
        $this->hooks_on_hookable_objects();
    }
}

<?php

namespace FCFProVendor\WPDesk\Composer\Codeception;

use FCFProVendor\Composer\Composer;
use FCFProVendor\Composer\IO\IOInterface;
use FCFProVendor\Composer\Plugin\Capable;
use FCFProVendor\Composer\Plugin\PluginInterface;
/**
 * Composer plugin.
 *
 * @package WPDesk\Composer\Codeception
 */
class Plugin implements \FCFProVendor\Composer\Plugin\PluginInterface, \FCFProVendor\Composer\Plugin\Capable
{
    /**
     * @var Composer
     */
    private $composer;
    /**
     * @var IOInterface
     */
    private $io;
    public function activate(\FCFProVendor\Composer\Composer $composer, \FCFProVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * @inheritDoc
     */
    public function deactivate(\FCFProVendor\Composer\Composer $composer, \FCFProVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * @inheritDoc
     */
    public function uninstall(\FCFProVendor\Composer\Composer $composer, \FCFProVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    public function getCapabilities()
    {
        return [\FCFProVendor\Composer\Plugin\Capability\CommandProvider::class => \FCFProVendor\WPDesk\Composer\Codeception\CommandProvider::class];
    }
}

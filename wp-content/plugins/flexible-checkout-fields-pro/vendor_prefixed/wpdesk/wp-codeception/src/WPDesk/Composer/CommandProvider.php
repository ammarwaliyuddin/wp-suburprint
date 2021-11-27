<?php

namespace FCFProVendor\WPDesk\Composer\Codeception;

use FCFProVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests;
use FCFProVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests;
use FCFProVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests;
/**
 * Links plugin commands handlers to composer.
 */
class CommandProvider implements \FCFProVendor\Composer\Plugin\Capability\CommandProvider
{
    public function getCommands()
    {
        return [new \FCFProVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests(), new \FCFProVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests(), new \FCFProVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests()];
    }
}

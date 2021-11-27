<?php

namespace FCFProVendor\WPDesk\Composer\Codeception\Commands;

use FCFProVendor\Composer\Command\BaseCommand as CodeceptionBaseCommand;
use FCFProVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Base for commands - declares common methods.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
abstract class BaseCommand extends \FCFProVendor\Composer\Command\BaseCommand
{
    /**
     * @param string $command
     * @param OutputInterface $output
     */
    protected function execAndOutput($command, \FCFProVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        \passthru($command);
    }
}

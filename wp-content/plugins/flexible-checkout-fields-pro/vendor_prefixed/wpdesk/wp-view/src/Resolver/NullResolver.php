<?php

namespace FCFProVendor\WPDesk\View\Resolver;

use FCFProVendor\WPDesk\View\Renderer\Renderer;
use FCFProVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \FCFProVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \FCFProVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \FCFProVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}

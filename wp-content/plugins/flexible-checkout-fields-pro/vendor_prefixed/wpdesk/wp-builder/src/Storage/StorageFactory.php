<?php

namespace FCFProVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \FCFProVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}

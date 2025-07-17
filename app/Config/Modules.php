<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Modules extends BaseConfig
{
    /**
     * Enable or disable auto-discovery.
     */
    public bool $enabled = true;

    /**
     * If true, auto-discovery will scan all Composer namespaces.
     * If false, only namespaces defined in `psr4` will be scanned.
     */
    public bool $discoverInComposer = true;

    /**
     * List of namespaces to scan for auto-discovery.
     *
     * Examples: 'Config', 'App', 'MyNamespace'
     */
    public array $autoDiscover = [
        'App',
    ];

    /**
     * Aliases for module locations.
     */
    public array $aliases = [];
}
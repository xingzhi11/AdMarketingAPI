<?php

namespace AdMarketingAPI\Kernel\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use AdMarketingAPI\Kernel\Supports\Config;

/**
 * Class ConfigServiceProvider.
 *
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['config'] = function ($app) {
            return new Config($app->getConfig());
        };
    }
}

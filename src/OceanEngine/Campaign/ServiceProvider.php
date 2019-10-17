<?php

namespace AdMarketingAPI\OceanEngine\Campaign;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 *
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['campaign'] = function ($app) {
            return new Campaign($app);
        };
    }
}
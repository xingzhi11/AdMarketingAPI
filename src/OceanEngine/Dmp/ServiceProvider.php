<?php

namespace AdMarketingAPI\OceanEngine\Dmp;

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
        $app['dmp'] = function ($app) {
            return new Dmp($app);
        };
    }
}
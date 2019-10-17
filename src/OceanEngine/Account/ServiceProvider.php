<?php

namespace AdMarketingAPI\OceanEngine\Account;

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
        $app['account'] = function ($app) {
            return new Account($app);
        };
    }
}
<?php

namespace AdMarketingAPI\Gdt\Dmp;

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
        $app['audience'] = function ($app) {
            return new OAuth($app);
        };
    }
}
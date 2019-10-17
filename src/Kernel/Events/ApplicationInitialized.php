<?php

namespace AdMarketingAPI\Kernel\Events;

use AdMarketingAPI\Kernel\ServiceContainer;

/**
 * Class ApplicationInitialized.
 *
 */
class ApplicationInitialized
{
    /**
     * @var \AdMarketingAPI\Kernel\ServiceContainer
     */
    public $app;

    /**
     * @param \AdMarketingAPI\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }
}

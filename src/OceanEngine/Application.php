<?php

namespace AdMarketingAPI\OceanEngine;

use AdMarketingAPI\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @property \EasyAdm\OceanEngine\Auth\AccessToken              $access_token
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        OAuth\ServiceProvider::class,
        Account\ServiceProvider::class,
        Campaign\ServiceProvider::class,
        Ad\ServiceProvider::class,
        // Creative\ServiceProvider::class,
        // Tools\ServiceProvider::class,
        // DMP\ServiceProvider::class,
        // DPA\ServiceProvider::class,
    ];

    /**
     * 普通模式.
     */
    const MODE_NORMAL = 'normal';

    /**
     * 沙箱模式.
     */
    const MODE_DEV = 'dev';

    /**
     * Const url.
     */
    const URL = [
        self::MODE_NORMAL => 'https://ad.toutiao.com/',
        self::MODE_DEV => 'https://test-ad.toutiao.com/',
    ];

    public function __construct(array $config = [], array $prepends = [])
    {
        if (isset($config['mode']) && self::MODE_DEV == $config['mode']) {
            $config['http']['base_uri'] = self::URL[self::MODE_DEV];
        } else {
            $config['http']['base_uri'] = self::URL[self::MODE_NORMAL];
        }

        parent::__construct($config, $prepends);
    }
}

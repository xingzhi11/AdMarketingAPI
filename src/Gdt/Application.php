<?php

namespace AdMarketingAPI\Gdt;

use AdMarketingAPI\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @property \EasyAdm\Gdt\Auth\AccessToken              $access_token
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        OAuth\ServiceProvider::class,
        DMP\ServiceProvider::class,
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
        self::MODE_NORMAL => 'https://api.e.qq.com/v1.1/',
        self::MODE_DEV => 'https://sandbox-api.e.qq.com/v1.1/',
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

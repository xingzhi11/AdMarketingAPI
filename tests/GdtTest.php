<?php

namespace AdMarketingAPI\Tests;

use AdMarketingAPI\Factory;
use Symfony\Component\Cache\Simple\RedisCache;

class GdtTest extends TestCase
{
    public function app()
    {
        $config = [
            'account_id' => "xxxxx",
            'app_id' => "xxxxx",
            'secret' => "xxxxxx",
            'oauth' => [
                'scopes' => "",
                // 回调链接
                'redirect_uri' => 'https://testbgmapi.innotechx.com/oceanengine/oauth/callback ',
            ],
            'log' => [ // optional
                'file' => './logs/gdt.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
                // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
            ],
            'mode' => 'normal',
        ];
       
        $app = Factory::Gdt($config);

        $redis = new \Redis();
        $redis->connect('redis_node', 6379);
        // 创建缓存实例
        $cache = new RedisCache($redis);
        // 替换应用中的缓存
        $app->rebind('cache', $cache);

        return $app;
    }
}

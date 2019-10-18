
<h1 align="left">AdMarketingAPI</h1>


## Requirement

1. PHP >= 7.1
2. **[Composer](https://getcomposer.org/)**
3. openssl 拓展
4. fileinfo 拓展


## Usage 

## Installation

```shell
$ composer require "xingzhi/admarketingapi:^1.0" -vvv
```

**OceanEngine 今日头条**

```php
<?php

use EasyWeChat\Factory;

$options = [
    'account_id' => 505397556292476,
    'app_id' => 1644911720042510,
    'secret' => '0a41de5d1f7f0109f1020574d2bfd6f9fbcb5201',
    'oauth' => [
        'redirect_uri' => 'oceanengine/oauth/callback',
    ],
    'log' => [ // optional
        'level' => 'info', 
        'file' => './logs/oceanengine.log',
    ],
    // ...
];

$app = Factory::oceanEngine($options);

// 通过auth_code 获取access_token
$oauth = $app->oauth;
$token = $oauth->getToken(true);

// 获取广告主账户余额
$fund = $app->account->fund(505397556292476);
// 获取一批广告主账户日预算 ,最多查询100个
$budget = $app->account->budget([505397556292476,...]);
// 更新广告主账户日预算
$result = $app->account->updateBudget(505397556292476, $app['account']::BUDGET_MODE_DAY, 1000);

// 拉取广告组列表
$filter = [
    'status' => 'CAMPAIGN_STATUS_ALL',
    'landing_type' => 'APP'
];
$adGroupList =  $app->campaign->filter($filter)->list(505397556292476);

// 拉取广告组数据报表
$filter = [
    'status' => 'CAMPAIGN_STATUS_ALL',
    'landing_type' => 'APP'
];
$adGroupReports = $app['campaign']->dateRange("2019-10-17","2019-10-17")
    ->filter($filter)
    ->list(505397556292476);
// 广告计划

// 广告创意

// 广告素材

```



## License

MIT

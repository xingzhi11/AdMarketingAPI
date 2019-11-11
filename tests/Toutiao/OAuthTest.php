<?php

namespace AdMarketingAPI\Tests\Toutiao;

use AdMarketingAPI\Tests\OceanEngineTest;

class OAuthTest extends OceanEngineTest
{
    public function testGetToken()
    {
        $_POST['auth_code'] = "6a274a5bc132d828994f8525e4279e7b97b532b0";
        $app = $this->app();
        $token = $app->oauth->getToken(true);
        dump($token);die;
    }
}
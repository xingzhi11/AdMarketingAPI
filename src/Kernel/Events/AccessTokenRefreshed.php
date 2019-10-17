<?php

namespace AdMarketingAPI\Kernel\Events;

use AdMarketingAPI\Kernel\AccessToken;

/**
 * Class AccessTokenRefreshed.
 */
class AccessTokenRefreshed
{
    /**
     * @var \AdMarketingAPI\Kernel\AccessToken
     */
    public $accessToken;

    /**
     * @param \AdMarketingAPI\Kernel\AccessToken $accessToken
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }
}

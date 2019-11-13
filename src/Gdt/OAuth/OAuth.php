<?php

namespace AdMarketingAPI\Gdt\OAuth;

use AdMarketingAPI\Kernel\AccessToken;
use AdMarketingAPI\Kernel\Contracts\AccessTokenInterface;
use AdMarketingAPI\Kernel\Exceptions\RuntimeException;
use Psr\Http\Message\RequestInterface;

class OAuth extends AccessToken
{
    /**
     * @var string
     */
    protected $cachePrefix = 'admarketingapi.gdt.';

    /**
     * @var string
     */
    protected $endpointToGetToken = "oauth/token";

    /**
     * 获取 Authorization Code
     * @var string
     */
    protected $authorizeUrl = "https://developers.e.qq.com/oauth/authorize";

    /**
     * OceanEngine OAuth2.0 URL.
     *
     * @param string $state
     *
     * @return string
     */
    public function getAuthUrl($state = '')
    {
        return $this->buildAuthUrlFromBase($this->authorizeUrl, $state);
    }

    /**
     * {@inheritdoc}.
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $query = http_build_query($this->getCodeFields($state), '', '&', PHP_QUERY_RFC1738);

        return $url.'?'.$query;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getCodeFields($state = null)
    {
        return [
            'client_id' => $this->app->config->get('app_id'),
            'state' => $state ?: md5(time()),
            'scope' => $this->app->config->get('oauth.scopes'),
            'redirect_uri' => $this->prepareCallbackUrl(),
        ];
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        $credentials = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->app['config']['app_id'],
            'client_secret' => $this->app['config']['secret'],
            'redirect_uri' => $this->prepareCallbackUrl(),
        ];
        $auth_code = $this->app->request->get('authorization_code');
        if (empty($auth_code)) {
            // get refresh_token form cache
            $refreshToken = $this->getCachedToken('refresh_token');
            // get access token by refresh_token
            if ($refreshToken) {
                $credentials['grant_type'] = 'refresh_token';
                $credentials['refresh_token'] = $refreshToken['refresh_token'];
            } 
        } else {
            // get access token by auth_code
            $credentials['authorization_code'] = $auth_code;
        }
    
        return $credentials;
    }

    /**
     * @param array $token
     *
     * @return \AdMarketingAPI\Kernel\Contracts\AccessTokenInterface
     */
    protected function setToken(array $token): AccessTokenInterface
    {
        if (!empty($token['advertiser_id'])) {
            $this->app['config']->set('account_id', $token['advertiser_id']);
        }
        $cache = $this->getCache();
        // access token
        $accessTokenKey = $this->getCacheKey('access_token');
       
        $cache->set($accessTokenKey, [
            $this->tokenKey => $token['access_token'],
            'expires_in' => $token['expires_in'],
        ], $token['expires_in'] - $this->safeSeconds);

        if (!$cache->has($accessTokenKey)) {
            throw new RuntimeException('Failed to cache access token.');
        }

        // refresh token
        $refreshTokenKey = $this->getCacheKey('refresh_token');
        $cache->set($refreshTokenKey, [
            'refresh_token' => $token['refresh_token'],
            'expires_in' => $token['refresh_token_expires_in'],
        ], $token['refresh_token_expires_in'] - $this->safeSeconds);

        if (!$cache->has($refreshTokenKey)) {
            throw new RuntimeException('Failed to cache refresh token.');
        }

        return $this;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array                              $requestOptions
     *
     * @return \Psr\Http\Message\RequestInterface
     *
     * @throws \AdMarketingAPI\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \AdMarketingAPI\Kernel\Exceptions\InvalidConfigException
     * @throws \AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException
     * @throws \AdMarketingAPI\Kernel\Exceptions\RuntimeException
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface
    {
        parse_str($request->getUri()->getQuery(), $query);
        $token = $this->getToken()[$this->tokenKey];
        $query = http_build_query($query);
        return $request->withHeader('Access-Token', $token)
                        ->withHeader('Content-Type', "application/json")
                        ->withUri($request->getUri()->withQuery($query));
    }
}

<?php

namespace AdMarketingAPI\Kernel\Supports\Traits;

use AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException;
use AdMarketingAPI\Kernel\ServiceContainer;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * Trait InteractsWithCache.
 *
 */
trait InteractsWithCache
{
    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache;

    /**
     * Get cache instance.
     *
     * @return \Psr\SimpleCache\CacheInterface
     *
     * @throws \AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException
     */
    public function getCache()
    {
        if ($this->cache) {
            return $this->cache;
        }

        if (property_exists($this, 'app') && $this->app instanceof ServiceContainer && isset($this->app['cache'])) {
            $this->setCache($this->app['cache']);

            // Fix PHPStan error
            assert($this->cache instanceof \Psr\SimpleCache\CacheInterface);

            return $this->cache;
        }

        return $this->cache = $this->createDefaultCache();
    }

    /**
     * Set cache instance.
     *
     * @param \Psr\SimpleCache\CacheInterface|\Psr\Cache\CacheItemPoolInterface $cache
     *
     * @return $this
     *
     * @throws \AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException
     */
    public function setCache($cache)
    {
        if (empty(\array_intersect([SimpleCacheInterface::class, CacheItemPoolInterface::class], \class_implements($cache)))) {
            throw new InvalidArgumentException(
                \sprintf('The cache instance must implements %s or %s interface.',
                    SimpleCacheInterface::class, CacheItemPoolInterface::class
                )
            );
        }

        if ($cache instanceof CacheItemPoolInterface) {
            if (!$this->isSymfony43()) {
                throw new InvalidArgumentException(sprintf('The cache instance must implements %s', SimpleCacheInterface::class));
            }
            $cache = new Psr16Cache($cache);
        }

        $this->cache = $cache;

        return $this;
    }

    /**
     * @return \Psr\SimpleCache\CacheInterface
     */
    protected function createDefaultCache()
    {
        if ($this->isSymfony43()) {
            return new Psr16Cache(new FilesystemAdapter('AdMarketingAPI', 1500));
        }

        return new FilesystemCache();
    }

    /**
     * @return bool
     */
    protected function isSymfony43(): bool
    {
        return \class_exists('Symfony\Component\Cache\Psr16Cache');
    }
}

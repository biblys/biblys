<?php
/*
 * Copyright (C) 2025 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Biblys\Service;

use Biblys\Exception\InvalidConfigurationException;
use DateInterval;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use MatthiasMullie\Scrapbook\Adapters\Apc;
use MatthiasMullie\Scrapbook\Adapters\Flysystem;
use MatthiasMullie\Scrapbook\KeyValueStore;
use MatthiasMullie\Scrapbook\Psr16\SimpleCache;
use Psr\SimpleCache\CacheInterface;

const ONE_DAY = 86400;

class CacheService implements CacheInterface
{
    private ?CacheInterface $cache = null;
    private int $ttl;

    /**
     * @throws InvalidConfigurationException
     */
    public function __construct(Config $config)
    {
        if ($config->isCacheEnabled()) {
            $this->ttl = $config->get("cache.ttl") ?? ONE_DAY;

            $store = $this->_getKeyValueStore($config);
            $this->cache = new SimpleCache($store);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache?->get($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        return $this->cache?->set($key, $value, $ttl ?? $this->ttl) ?? false;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        return $this->cache?->delete($key) ?? false;
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        return $this->cache?->clear() ?? false;
    }

    /**
     * @inheritDoc
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return $this->cache?->getMultiple($keys, $default) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        return $this->cache?->setMultiple($values, $ttl) ?? false;
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple(iterable $keys): bool
    {
        return $this->cache?->deleteMultiple($keys) ?? false;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->cache?->has($key) ?? false;
    }

    /**
     * @throws InvalidConfigurationException
     */
    private function _getKeyValueStore(Config $config): KeyValueStore
    {
        if ($config->get("cache.driver") === "apc") {
            return new Apc();
        }

        if ($config->get("cache.driver") === "filesystem") {
            $cacheDir = __DIR__ . "/../../../content/cache";
            $adapter = new LocalFilesystemAdapter($cacheDir);
            $filesystem = new Filesystem($adapter);
            return new Flysystem($filesystem);
        }

        throw new InvalidConfigurationException("Invalid cache driver {$config->get("cache.driver")}");
    }
}
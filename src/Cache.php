<?php declare(strict_types=1);

namespace Jcsp\Cache;

use Closure;
use Jcsp\Cache\Lock\LockContract;
use Swoft\Cache\Contract\CacheAdapterInterface;
use Swoft\Redis\Connection\Connection;
use Swoft\Redis\Connection\ConnectionManager;
use Swoft\Redis\Exception\RedisException;
use Throwable;
use Swoft;

/**
 * Class Cache
 *
 * @method static bool has($key)
 * @method static bool set($key, $value, $ttl = null)
 * @method static get($key, $default = null)
 * @method static delete($key)
 * @method static bool clear()
 * @method static array getMultiple($keys, $default = null)
 * @method static bool setMultiple($values, $ttl = null)
 * @method static bool deleteMultiple($keys)
 * @method static CacheAdapterInterface getAdapter()
 * @method static void setAdapter(CacheAdapterInterface $adapter)
 * @method static LockContract lock($key, int $ttl = 0, $value = null)
 * @method static remember($key, $ttl, Closure $callback)
 * @method static rememberForever($key, Closure $callback)
 * @method static bool forever($key, $value)
 * @method static pull($key, $default = null)
 * @method static clearTrigger(string $event, array $args = [], $target = null)
 *
 */
class Cache
{
    // Cache manager bean name
    public const LOCK    = 'cache.lock';
    public const MANAGER    = 'cache.manager';
    public const ADAPTER    = 'cache.adapter';
    public const SERIALIZER = 'cache.serializer';

    public const ASP_BEFORE = 'before';
    public const ASP_AFTER = 'after';

    public const CLEAR_EVENT = 'cache.event.clear';
    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     * @throws RedisException
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $cacheManager = self::manager();
        return $cacheManager->{$method}(...$arguments);
    }
    /**
     * @return CacheManager
     */
    public static function manager(): CacheManager
    {
        return Swoft::getBean(self::MANAGER);
    }
}

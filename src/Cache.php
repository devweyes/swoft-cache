<?php declare(strict_types=1);

namespace Jcsp\Cache;

use Swoft\Cache\Contract\CacheAdapterInterface;
use Swoft\Redis\Connection\Connection;
use Swoft\Redis\Connection\ConnectionManager;
use Swoft\Redis\Exception\RedisException;
use Throwable;

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
 */
class Cache
{
    public const ASP_BEFORE = 'before';
    public const ASP_AFTER = 'after';

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     * @throws RedisException
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $cacheManager = \Swoft\Cache\Cache::manager();
        return $cacheManager->{$method}(...$arguments);
    }
}

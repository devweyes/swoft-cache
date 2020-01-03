<?php declare(strict_types=1);

namespace Jcsp\Cache\Register;

use Jcsp\Cache\Helper\Str;
use Swoft\Log\Helper\CLog;
use Swoft\Stdlib\Helper\StringHelper;

/**
 * Class CacheRegister
 *
 * @since 2.0
 */
class CacheRegister
{
    /**
     * cache config array
     *
     * @var array
     *
     * @example
     * [
     * ]
     */
    private static $data = [];
    /**
     * clear config array
     *
     * @var array
     *
     * @example
     * [
     * ]
     */
    private static $clearListenerData = [];

    /**
     * Register relation
     * @param array $data
     * @param string $className
     * @param string $methodName
     * @param string $type
     */
    public static function register(
        array $data,
        string $className,
        string $methodName,
        string $type
    ): void {
        self::$data[$className][$methodName][$type] = $data;
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $type
     * @return bool
     */
    public static function get(string $className, string $methodName, string $type)
    {
        return self::$data[$className][$methodName][$type] ?? [];
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $type
     * @return bool
     */
    public static function has(string $className, string $methodName, string $type)
    {
        return !empty(self::$data[$className][$methodName][$type]);
    }

    /**
     * Register relation
     * @param array $data
     * @param string $className
     * @param string $methodName
     * @param string $type
     */
    public static function registerClearData(
        array $data,
        string $className,
        string $methodName
    ): void {
        if (!empty($data[3]) && empty(self::$clearListenerData[$data[3]])) {
            self::$clearListenerData[$data[3]] = compact('className', 'methodName', 'data');
        }
        //self::$clearListenerData["$className@$methodName"] = compact('className', 'methodName', 'data');
    }

    /**
     * @return array
     */
    public static function getClearData(): array
    {
        return self::$clearListenerData;
    }

    /**
     * @param string $prefix
     * @param array $arguments
     * @param string|null $value
     * @return string
     */
    public static function formatedKey(string $prefix, array $arguments, ?string $value = null): string
    {
        $key = Str::formatCacheKey($prefix, $arguments, $value);
        if (strlen($key) > 64) {
            CLog::warning('The cache key length is too long. The key is ' . $key);
        }
        return $key;
    }
}

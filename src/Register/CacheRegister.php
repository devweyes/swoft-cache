<?php declare(strict_types=1);

namespace Jcsp\Cache\Register;

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
        self::$data[$className][$emthodName][$type] = $data;
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
}

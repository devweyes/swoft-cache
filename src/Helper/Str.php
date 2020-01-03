<?php declare(strict_types=1);

namespace Jcsp\Cache\Helper;

use Swoft\Stdlib\Helper\Arr;

class Str
{

    /**
     * Format cache key with prefix and arguments.
     */
    public static function formatCacheKey(string $prefix, array $arguments, ?string $value = null): string
    {
        if ($value !== null) {
            if ($matches = self::parseCacheKey($value)) {
                foreach ($matches as $search) {
                    $k = str_replace(['#{', '}'], '', $search);
                    $value = self::replaceFirst($search, (string) self::dataGet($arguments, $k), $value);
                }
            }
        } else {
            $value = implode(':', $arguments);
        }
        return $prefix . ':' . $value;
    }
    /**
     * Parse expression of value.
     */
    public static function parseCacheKey(string $value): array
    {
        preg_match_all('/\#\{[\w\.]+\}/', $value, $matches);
        return $matches[0] ?? [];
    }
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param array|int|string $key
     * @param null|mixed $default
     * @param mixed $target
     */
    public static function dataGet($target, $key, $default = null)
    {
        if ($key === null) {
            return $target;
        }
        $key = is_array($key) ? $key : explode('.', is_int($key) ? (string) $key : $key);
        while (($segment = array_shift($key)) !== null) {
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (! is_array($target)) {
                    return value($default);
                }
                $result = [];
                foreach ($target as $item) {
                    $result[] = self::dataGet($item, $key);
                }
                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }
            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }
        return $target;
    }
}

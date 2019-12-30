<?php declare(strict_types=1);

namespace Jcsp\Cache;

use Swoft\Console\Application;
use Swoft\Console\ConsoleDispatcher;
use Swoft\Console\Router\Router;
use Swoft\Helper\ComposerJSON;
use Swoft\Serialize\PhpSerializer;
use Swoft\SwoftComponent;
use function dirname;

/**
 * class AutoLoader
 *
 * @since 2.0
 */
final class AutoLoader extends SwoftComponent
{
    /**
     * @return bool
     */
    public function enable(): bool
    {
        return true;
    }

    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Metadata information for the component
     *
     * @return array
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__).'/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

    /**
     * {@inheritDoc}
     */
    public function beans(): array
    {
        return [
            /*** cache 配置 ************************/
            Cache::MANAGER => [
                'class' => CacheManager::class,
                'adapter' => bean(Cache::ADAPTER),
                'lockAdapter' => Cache::LOCK
            ],
            Cache::ADAPTER => [
                'class' => \Swoft\Cache\Adapter\RedisAdapter::class,
                'redis' => bean('redis.pool'),
                'prefix' => config('name') . ':',
                'serializer' => bean(Cache::SERIALIZER),
            ],
            Cache::LOCK => [
                'class' => \Jcsp\Cache\Lock\RedisLock::class,
                'redis' => bean('redis.pool'),
                'prefix' => 'lock:'
            ],
            Cache::SERIALIZER => [
                'class' => PhpSerializer::class
            ],
        ];
    }
}

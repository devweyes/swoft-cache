<?php

namespace Jcsp\Cache;

use Closure;
use Jcsp\Cache\Lock\LockContract;
use Swoft\Bean\BeanFactory;

trait CacheAbleTrait
{

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     * @param $key
     * @param $ttl
     * @param Closure $callback
     * @return mixed
     */
    public function remember($key, $ttl, Closure $callback)
    {
        $value = $this->get($key);

        if ($value !== null) {
            return $value;
        }
        $this->put($key, $value = $callback(), $ttl);

        return $value;
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function forever($key, $value): bool
    {
        return $this->set($key, $value, -1);
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result forever.
     *
     * @param string $key
     * @param \Closure $callback
     * @return mixed
     */
    public function rememberForever($key, Closure $callback)
    {
        $value = $this->get($key);

        if ($value !== null) {
            return $value;
        }
        $this->forever($key, $value = $callback());

        return $value;
    }

    /**
     * Get a lock instance.
     *
     * @param  $key
     * @param int $seconds
     * @param string $owner
     * @return LockContract
     */
    public function lock($key, int $ttl = 0, $value = ''): LockContract
    {
        /** @var LockContract $lock */
        $lock = BeanFactory::getBean(Cache::LOCK);
        $lock->reset($key, $ttl, $value);
        return $lock;
    }
}

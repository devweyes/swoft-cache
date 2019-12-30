<?php declare(strict_types=1);

namespace Jcsp\Cache;

use Jcsp\Cache\Lock\LockContract;
use Jcsp\Cache\Lock\LockTrait;
use Psr\SimpleCache\CacheInterface;
use Swoft\Cache\Contract\CacheAdapterInterface;

/**
 * Class CacheManager
 */
class CacheManager implements CacheInterface
{
    use LockTrait, CacheAbleTrait;
    /**
     * Current used cache adapter driver
     *
     * @var CacheAdapterInterface
     */
    protected $adapter;
    /**
     * Lock Contract
     *
     * @var LockContract
     */
    protected $lockAdapter;

    /**
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->adapter->has($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null): bool
    {
        return $this->adapter->set($key, $value, $ttl);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get($key, $default = null)
    {
        return $this->adapter->get($key, $default);
    }

    /**
     * @param string $key
     * @return bool|mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function delete($key)
    {
        return $this->adapter->get($key);
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {
        return $this->adapter->clear();
    }

    /**
     * @param iterable $keys
     * @param null $default
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getMultiple($keys, $default = null): array
    {
        return $this->adapter->getMultiple($keys, $default);
    }

    /**
     * @param iterable $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool
    {
        return $this->adapter->setMultiple((array)$values, $ttl);
    }

    /**
     * @param iterable $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool
    {
        return $this->adapter->deleteMultiple((array)$keys);
    }

    /**
     * @return CacheAdapterInterface
     */
    public function getAdapter(): CacheAdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @param CacheAdapterInterface $adapter
     */
    public function setAdapter(CacheAdapterInterface $adapter): void
    {
        $this->adapter = $adapter;
    }
}

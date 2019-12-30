<?php

namespace Jcsp\Cache\Lock;

use Jcsp\Cache\Exception\LockTimeoutException;
use Jcsp\Core\Helper\Str;

abstract class Lock implements LockContract
{
    /**
     * 锁名
     *
     * @var string
     */
    protected $name;

    /**
     * 锁时间 seconds
     *
     * @var int
     */
    protected $seconds;

    /**
     * The scope identifier of this lock.
     *
     * @var string
     */
    protected $owner;

    /**
     * @param $name
     * @param $seconds
     * @param $owner
     * @throws \Exception
     */
    public function reset($name, $seconds, $owner):void
    {
        if ($owner === '') {
            $owner = Str::random();
        }

        $this->name = $name;
        $this->owner = $owner;
        $this->seconds = $seconds;
    }
    /**
     * Attempt to acquire the lock.
     *
     * @return bool
     */
    abstract public function acquire(): bool;

    /**
     * Release the lock.
     *
     * @return bool
     */
    abstract public function release(): bool;

    /**
     * Returns the owner value written into the driver for this lock.
     *
     * @return string
     */
    abstract protected function getCurrentOwner(): string;

    /**
     * Attempt to acquire the lock.
     *
     * @param callable|null $callback
     * @return mixed
     */
    public function get($callback = null)
    {
        $result = $this->acquire();

        if ($result && is_callable($callback)) {
            try {
                return $callback();
            } finally {
                $this->release();
            }
        }

        return $result;
    }

    /**
     * Attempt to acquire the lock for the given number of seconds.
     *
     * @param int $seconds
     * @param callable|null $callback
     * @return bool
     *
     * @throws \Illuminate\Contracts\Cache\LockTimeoutException
     */
    public function block($seconds, $callback = null): bool
    {
        $starting = $this->currentTime();

        while (!$this->acquire()) {
            usleep(250 * 1000);

            if ($this->currentTime() - $seconds >= $starting) {
                throw new LockTimeoutException;
            }
        }

        if (is_callable($callback)) {
            try {
                return $callback();
            } finally {
                $this->release();
            }
        }

        return true;
    }

    /**
     * Returns the current owner of the lock.
     *
     * @return string
     */
    public function owner(): string
    {
        return $this->owner;
    }

    /**
     * Determines whether this lock is allowed to release the lock in the driver.
     *
     * @return bool
     */
    protected function isOwnedByCurrentProcess(): bool
    {
        return $this->getCurrentOwner() === $this->owner;
    }
}

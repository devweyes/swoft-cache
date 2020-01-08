<?php

namespace Jcsp\Cache\Lock;

trait LockTrait
{
    /**
     * Lock Contract
     *
     * @var LockContract
     */
    protected $lockAdapter;
    /**
     * Release the lock.
     *
     * @return bool
     */
    public function release(): bool
    {
        return $this->lockAdapter->release();
    }
    /**
     * Releases this lock in disregard of ownership.
     *
     * @return void
     */
    public function forceRelease(): void
    {
        $this->lockAdapter->forceRelease();
    }
    /**
     * Attempt to acquire the lock.
     *
     * @param callable|null $callback
     * @return mixed
     */
    public function get($callback = null)
    {
        return $this->lockAdapter->get($callback);
    }

    /**
     * Attempt to acquire the lock for the given number of seconds.
     *
     * @param int $seconds
     * @param callable|null $callback
     * @return bool
     */
    public function block($seconds, $callback = null): bool
    {
        return $this->lockAdapter->block($seconds, $callback);
    }

    /**
     * Returns the current owner of the lock.
     *
     * @return string
     */
    public function owner(): string
    {
        return $this->lockAdapter->owner();
    }
}

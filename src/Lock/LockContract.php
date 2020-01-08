<?php

namespace Jcsp\Cache\Lock;

interface LockContract
{
    /**
     * Attempt to acquire the lock.
     *
     * @param callable|null $callback
     * @return mixed
     */
    public function get($callback = null);

    /**
     * Attempt to acquire the lock for the given number of seconds.
     *
     * @param int $seconds
     * @param callable|null $callback
     * @return bool
     */
    public function block($seconds, $callback = null): bool;

    /**
     * Release the lock.
     *
     * @return bool
     */
    public function release(): bool;

    /**
     * Returns the current owner of the lock.
     *
     * @return string
     */
    public function owner(): string;

    /**
     * Releases this lock in disregard of ownership.
     *
     * @return void
     */
    public function forceRelease(): void;

    /**
     * @param $name
     * @param $seconds
     * @param $owner
     * @throws \Exception
     */
    public function reset($name, $seconds, $owner): void;
}

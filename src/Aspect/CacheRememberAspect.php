<?php

namespace Jcsp\Cache\Aspect;

use Jcsp\Cache\Register\CacheRegister;
use Swoft\Aop\Annotation\Mapping\After;
use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\AfterThrowing;
use Swoft\Aop\Annotation\Mapping\Around;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\Before;
use Swoft\Aop\Annotation\Mapping\PointAnnotation;
use Swoft\Aop\Annotation\Mapping\PointBean;
use Swoft\Aop\Point\JoinPoint;
use Swoft\Aop\Point\ProceedingJoinPoint;
use Jcsp\Cache\Annotation\Mapping\CacheRemember;
use Swoft\Cache\Cache;
use Swoft\Cache\CacheManager;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class RelationPassiveAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointAnnotation(include={CacheRemember::class})
 */
class CacheRememberAspect
{
    /**
     * @Inject()
     * @var CacheManager
     */
    private $redis;
    /**
     * @Around()
     *
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        // Before around
        $className = $proceedingJoinPoint->getClassName();
        $methodName = $proceedingJoinPoint->getMethod();

        $has = CacheRegister::has($className, $methodName, 'cacheRemember');
        $has && ([$key, $ttl, $putListener, $clearListener] = CacheRegister::get($className, $methodName, 'cacheRemember'));

        if ($has && $cache = $this->getCache($key)) {
            return $cache;
        }
        $result = $proceedingJoinPoint->proceed();
        // After around
        $this->putCache((string)$key, $result, (int)$ttl, (string)$putListener);
        return $result;
    }

    /**
     * get cache
     * @param string $key
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function getCache(string $key)
    {
        return $this->redis->get($key);
    }

    /**
     * put cache
     * @param string $key
     * @param $result
     * @param int $ttl
     * @param string $putListener
     * @throws Swoft\Bean\Exception\ContainerException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function putCache(string $key, $result, int $ttl, string $putListener)
    {
        $this->redis->set($key, $result, $ttl);
        if (!empty($putListener)) {
            Swoft::trigger($putListener, $key, $result, $ttl);
        }
    }
}

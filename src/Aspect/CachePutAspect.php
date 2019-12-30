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
use Jcsp\Cache\Annotation\Mapping\CachePut;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Cache\Cache;
use Swoft\Cache\CacheManager;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class RelationPassiveAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointAnnotation(include={CachePut::class})
 */
class CachePutAspect
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

        $has = CacheRegister::has($className, $methodName, 'cachePut');
        $has && ([$key, $val, $ttl, $clearListener] = CacheRegister::get($className, $methodName, 'cachePut'));

        $result = $proceedingJoinPoint->proceed();
        // After around
        if ($has) {
            $data = $result;
            if (!empty($val)) {
                $data = $val;
            }
            $this->redis->set($key, $data, (int)$ttl);
        }
        return $result;
    }
}

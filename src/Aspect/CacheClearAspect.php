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
use Jcsp\Cache\Annotation\Mapping\CacheClear;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;
use Swoft\Cache\Cache;
use Swoft\Cache\CacheManager;
use Jcsp\Cache\Cache as CacheStatic;

/**
 * Class RelationPassiveAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointAnnotation(include={CacheClear::class})
 */
class CacheClearAspect
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

        $has = CacheRegister::has($className, $methodName, 'cacheClear');
        $has && ([$key, $position] = CacheRegister::get($className, $methodName, 'cacheClear'));
        if ($has && $position === CacheStatic::ASP_BEFORE) {
            $this->redis->delete($key);
        }
        $result = $proceedingJoinPoint->proceed();
        // After around
        if ($has && $position === CacheStatic::ASP_AFTER) {
            $this->redis->delete($key);
        }
        return $result;
    }
}

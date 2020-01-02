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
use Jcsp\Cache\CacheManager;
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
        $argsMap = $proceedingJoinPoint->getArgsMap();

        $has = CacheRegister::has($className, $methodName, 'cacheRemember');

        if (!$has) {
            return $proceedingJoinPoint->proceed();
        }

        [$key, $ttl, $putListener,] = CacheRegister::get($className, $methodName, 'cacheRemember');

        $prefix = $key ? '' : "$className@$methodName";
        $key = CacheRegister::formatedKey($prefix, $argsMap, $key);

        return $this->redis->remember(
            $key,
            (int)$ttl,
            static function () use ($proceedingJoinPoint, $putListener, $key, $ttl) {
                $result = $proceedingJoinPoint->proceed();
                if (!empty($putListener)) {
                    Swoft::trigger($putListener, $key, $result, $ttl);
                }
                return $result;
            }
        );
    }
}

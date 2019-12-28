<?php

namespace Jcsp\Cache\Aspect;

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
     * @Before()
     */
    public function before()
    {
        // before
    }

    /**
     * @After()
     */
    public function after()
    {
        // After
    }

    /**
     * @AfterReturning()
     *
     * @param JoinPoint $joinPoint
     *
     * @return mixed
     */
    public function afterReturn(JoinPoint $joinPoint)
    {
       return '';
    }

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
        $result = $proceedingJoinPoint->proceed();
        // After around

        return $result;
    }

    /**
     * @param \Throwable $throwable
     *
     * @AfterThrowing()
     */
    public function afterThrowing(\Throwable $throwable)
    {
        // afterThrowing
    }
}

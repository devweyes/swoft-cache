<?php declare(strict_types=1);

namespace Jcsp\Cache\Annotation\Parser;

use Jcsp\Cache\Register\CacheRegister;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Jcsp\Cache\Annotation\Mapping\CacheRemember;

/**
 * Class CacheRememberParser
 *
 * @AnnotationParser(CacheRemember::class)
 * @since 2.0
 * @package Jcsp\Cache\Annotation\Parser
 */
class CacheRememberParser extends Parser
{
    /**
     * @param int $type
     * @param CacheRemember $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('Annotation CacheClear shoud on method!');
        }
        $key = $annotationObject->getKey() ?: config('name') . ":$this->className@$this->methodName";
        $val = $annotationObject->getVal();
        $ttl = $annotationObject->getTtl();
        $putListener = $annotationObject->getPutListener();
        $clearListener = $annotationObject->getClearListener();
        $data = compact('key', 'val', 'ttl', 'putListener', 'clearListener');
        CacheRegister::register($data, $this->className, $this->methodName, 'cacheRemember');
        return [];
    }
}

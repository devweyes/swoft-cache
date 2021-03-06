<?php declare(strict_types=1);

namespace Jcsp\Cache\Annotation\Parser;

use Jcsp\Cache\Register\CacheRegister;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Jcsp\Cache\Annotation\Mapping\CachePut;

/**
 * Class CachePutParser
 *
 * @AnnotationParser(CachePut::class)
 * @since 2.0
 * @package Jcsp\Cache\Annotation\Parser
 */
class CachePutParser extends Parser
{
    /**
     * @param int $type
     * @param CachePut $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('Annotation CacheClear shoud on method!');
        }
        $key = $annotationObject->getKey();
        $val = $annotationObject->getVal();
        $ttl = $annotationObject->getTtl();
        $clearListener = $annotationObject->getClearListener();
        $data = [
            $key, $val, $ttl, $clearListener
        ];
        CacheRegister::register($data, $this->className, $this->methodName, 'cachePut');
        if (!empty($clearListener)) {
            CacheRegister::registerClearData($data, $this->className, $this->methodName);
        }
        return [];
    }
}

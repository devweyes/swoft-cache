<?php declare(strict_types=1);

namespace Jcsp\Cache\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Jcsp\Cache\Annotation\Mapping\CacheClear;

/**
 * Class CacheClearParser
 *
 * @AnnotationParser(CacheClear::class)
 * @since 2.0
 * @package Jcsp\Cache\Annotation\Parser
 */
class CacheClearParser extends Parser
{
    /**
     * @param int $type
     * @param CacheClear $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('Annotation CacheClear shoud on method!');
        }

        return [];
    }
}

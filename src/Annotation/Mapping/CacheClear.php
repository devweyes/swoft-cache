<?php declare(strict_types=1);
/**
 * like Cache::clear()
 */

namespace Jcsp\Cache\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;
use Jcsp\Cache\Cache;

/**
 * Class CacheClear
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("key", type="string"),
 *     @Attribute("position", type="string"),
 * })
 *
 * @since 2.0
 */
final class CacheClear
{
    /**
     * @var string
     */
    private $key = '';
    /**
     * @var string
     */
    private $position = Cache::ASP_AFTER;

    /**
     * Entity constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->key = $values['value'];
        } elseif (isset($values['key'])) {
            $this->key = $values['key'];
        }
        if (isset($values['position'])) {
            $this->position = $values['position'];
        }
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }
}

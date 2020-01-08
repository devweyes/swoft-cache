<?php declare(strict_types=1);
/**
 * like Cache::put()
 */
namespace Jcsp\Cache\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;
use Jcsp\Cache\Cache;

/**
 * Class CachePut
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("key", type="string"),
 *     @Attribute("val", type="string"),
 *     @Attribute("ttl", type="int"),
 *     @Attribute("clearListener", type="string"),
 * })
 *
 * @since 2.0
 */
final class CachePut
{
    /**
     * @var string
     */
    private $key = '';
    /**
     * @var string
     */
    private $val = '';
    /**
     * @var int
     */
    private $ttl = -1;
    /**
     * @var string
     */
    private $clearListener = '';
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
        if (isset($values['val'])) {
            $this->val = $values['val'];
        }
        if (isset($values['ttl'])) {
            $this->ttl = (int)$values['ttl'];
        }
        if (isset($values['clearListener'])) {
            $this->clearListener = $values['clearListener'];
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
    public function getVal(): string
    {
        return $this->val;
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }
    /**
     * @return string
     */
    public function getClearListener(): string
    {
        return $this->clearListener;
    }
}

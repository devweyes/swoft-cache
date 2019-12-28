<?php declare(strict_types=1);
/**
 * like Cache::remember()
 */
namespace Jcsp\Cache\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class CacheRemember
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("key", type="string"),
 *     @Attribute("val", type="string"),
 *     @Attribute("ttl", type="int"),
 *     @Attribute("group", type="string"),
 * })
 *
 * @since 2.0
 */
final class CacheRemember
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $val;
    /**
     * @var int
     */
    private $ttl = -1;
    /**
     * @var string
     */
    private $putListener = '';
    /**
     * @var string
     */
    private $clearListener = '';

    /**
     * @var string
     */
    private $group = 'default';

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
        if (isset($values['group'])) {
            $this->group = $values['group'];
        }
    }

    /**
     * @return string
     */
    public function getPutListener(): string
    {
        return $this->putListener;
    }

    /**
     * @return string
     */
    public function getClearListener(): string
    {
        return $this->clearListener;
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
    public function getGroup(): string
    {
        return $this->group;
    }
}

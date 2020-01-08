<?php declare(strict_types=1);

namespace Jcsp\Cache;

use Jcsp\Cache\Register\CacheRegister;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Stdlib\Helper\PhpHelper;

/**
 * Class CacheClearListener
 *
 * @Listener(event=Cache::CLEAR_EVENT)
 * @package Jcsp\Cache
 */
class CacheClearListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $args = $event->getParams();

        if (!empty($args) && count($args) === 2) {
            $data = CacheRegister::getClearData();
            $data = $data[$args[0]] ?? null;
            if (!empty($data) && is_array($data)) {
                $this->clear($args, $data);
            }
        }
    }

    /**
     * clear cache
     * @param array $args
     * @param array $data
     */
    public function clear(array $args, array $data): void
    {
        $argsMap = $args[1] ?? [];
        $key = $data['data'][0] ?? '';
        $prefix = $key ? '' : $data['className'] . '@' . $data['methodName'];
        $key = CacheRegister::formatedKey($prefix, $argsMap, $key);
        Cache::delete($key);
    }
}

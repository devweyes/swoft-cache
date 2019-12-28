<?php

namespace SwoftTest\Cache;

use PHPUnit\Framework\TestCase;
use Swoft\Cache\Adapter\ArrayAdapter;
use Swoft\Cache\CacheManager;
use Swoft\Cache\Exception\InvalidArgumentException;
use SwoftTest\Testing\Concern\CommonTestAssertTrait;

/**
 * Class CacheTest
 */
class CacheManagerTest extends TestCase
{
    use CommonTestAssertTrait;

    /**
     * @throws InvalidArgumentException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testCache(): void
    {
        $cache = new CacheManager();
        $cache->setAdapter(new ArrayAdapter());

        $key   = 'test:key';
        $tests = [
            'string',
            1,
            1.235,
            false,
            [
                'int' => 1,
                'float' => 1.234,
                'bool' => true,
                'null' => null,
                'string' => 'value'
            ],
        ];

        foreach ($tests as $value) {
            $ok = $cache->set($key, $value);

            $this->assertTrue($ok);
            $this->assertTrue($cache->has($key));

            $this->assertEquals($value, $cache->get($key));

            $this->assertTrue($cache->delete($key));
            $this->assertFalse($cache->has($key));
        }

        $this->assertFalse($cache->has('not-exist'));
        $this->assertNull($cache->get('not-exist'));
        $this->assertSame('default', $cache->get('not-exist', 'default'));

        foreach ([12, true, null, ''] as $key) {
            $this->assetException(function () use ($cache, $key) {
                $cache->set($key, 'value');
            }, InvalidArgumentException::class);

            $this->assetException(function () use ($cache, $key) {
                $cache->get($key);
            }, InvalidArgumentException::class);
        }

        /**
         * clear
         */
        $cache->set($key, 'value');
        $this->assertTrue($cache->clear());
        $this->assertNull($cache->get($key));

        /**
         * setMultiple & getMultiple
         */
        $multiple     = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $setMulResult = $cache->setMultiple($multiple);
        $this->assertTrue($setMulResult);
        $getMulResult = $cache->getMultiple(['key1', 'key2']);
        $this->assertEquals($multiple, $getMulResult);
    }
}

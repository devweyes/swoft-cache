# cache 缓存

1\. 介绍
----------------
* 完全遵循PSR16 cache组件，支持传统静态调用，注入调用，注解调用，事件绑定等。只需一个闭包即可支持分布式原子锁。

2\. 配置
----------------

2.1 composer
```
composer require jcsp/cache
```

2.2 无需额外配置，```Autoload.php```中包含默认配置（可覆盖 key名需相同）。

```php
<?php

use Jcsp\Cache\Cache;
use Swoft\Serialize\PhpSerializer;
use Jcsp\Cache\Annotation\Mapping\CacheRemember;

return [
    //cache主要配置
    Cache::MANAGER => [
        'class' => \Jcsp\Cache\CacheManager::class,
        'adapter' => bean(Cache::ADAPTER),
        'lockAdapter' => Cache::LOCK
    ],
    //cache选择器 redis
    Cache::ADAPTER => [
        'class' => \Swoft\Cache\Adapter\RedisAdapter::class,
        'redis' => bean('redis.pool'),
        'prefix' => config('name') . ':',
        'serializer' => bean(Cache::SERIALIZER),
    ],
    //cache原子锁配置
    Cache::LOCK => [
        'class' => \Jcsp\Cache\Lock\RedisLock::class,
        'redis' => bean('redis.pool'),
        'prefix' => 'lock:'
    ],
    //cache序列化
    Cache::SERIALIZER => [
        'class' => PhpSerializer::class
    ],
];

```

3\. 使用
----------------

3.1 基本使用

```php
<?php

namespace App\Rpc\Service;

use Jcsp\Cache\Cache;
use Jcsp\Cache\CacheManager;

/**
 * Class TestingService
 * @since 2.0
 * @Service()
 */
class UserService implements UserInterface
{
  /**
   * @Inject()
   * @var CacheManager
   */
   private $cache;
  /**
   * 缓存静态调用
   */
   public function statics()
    {
        //缓存30秒
        Cache::set('key','value', 30);
        
        //缓存获取
        $value = Cache::get('key');
        
        //缓存清除
        Cache::delete('key');
        
        //30秒缓存 缓存不存在则查库 查库数据再存入缓存
        $value = Cache::remember('users', 30, function () {
              return DB::table('users')->get();
        });
        //获取并删除
        $value = Cache::pull('key');
        
        //数据永久存储  需要调用delete清除
        Cache::forever('key', 'value');
        
        //缓存不存在则查库 查库数据再永久存入缓存 需要调用delete清除
        $value = Cache::rememberForever('users', function () {
              return DB::table('users')->get();
        });
    }
  /**
   * 注入方式调用
   */
   public function di()
    {
        //缓存30秒
        $this-cache->set('key','value', 30);
        
        //缓存获取
        $value = $this-cache->get('key');
        
        //缓存清除
        $this-cache->delete('key');
        
        //30秒缓存 缓存不存在则查库 查库数据再存入缓存
        $value = $this-cache->remember('users', 30, function () {
              return DB::table('users')->get();
        });
        //获取并删除
        $value = $this-cache->pull('key');
        
        //数据永久存储  需要调用delete清除
        $this-cache->forever('key', 'value');
        
        //缓存不存在则查库 查库数据再永久存入缓存 需要调用delete清除
        $value = $this-cache->rememberForever('users', function () {
              return DB::table('users')->get();
        });
    }
    
    
  /**
   * 注解方式调用
   * 缓存30秒，否则从function拿
   * 
   * key为空则以class@action作为key，忽略参数
   * key支持参数传入，key规则其他注解通用
   * 当putListener不为空，触发缓存写入则触发此事件
   * 
   * @CacheRemember(ttl=30, key="cache1_#{id}", putListener="CACHE_PUT")
   */
   public function cache1($id)
   {
      // TODO something
   }
    
  /**
   * 每次都触发写入缓存
   * 当clearListener不为空，调用此事件则清除缓存
   * 
   * @CachePut(ttl=30, clearListener="CACHE_CLEAR")
   */
   public function cache2($id)
   {
      // TODO something
   }
  /**
   * 每次都触发清除缓存
   * position标识清除操作的位置，执行前或执行后
   * 
   * @CacheClear(position=Cache::ASP_AFTER)
   */
   public function cache3($id)
   {
      // TODO something
   }
}
```
3.2 事件支持

* 缓存清除事件，事件方式清除，无需关心key名。仅支持注解CachePut与CacheRemember。

```php
use Jcsp\Cache\Cache;
/**
* @param string $event clearListener对应字符串
* @param array $args 参数键值对，与方法参数对应，如['id'=>1]
*/
Cache::clearTrigger(string $event, array $args = [])
```


3.3 原子锁

* 暂只支持reids驱动
* 常见场景：分布式定时任务。前提需连接同一缓存服务器

```php
<?php

    if (Cache::lock('foo', 10)->get()) {
        // 获取锁定10秒...

        Cache::lock('foo')->release();
    }
        
```
```php
<?php

  Cache::lock('foo')->get(function () {
      // 锁无限期获取并自动释放...
  });
```
```php
<?php

  if (Cache::lock('foo', 10)->block(5)) {
      // 等待最多5秒后获取锁定...
  });
```
```php
<?php

  Cache::lock('foo', 10)->block(5, function () {
      // 等待最多5秒后获取锁定...
  });   

```

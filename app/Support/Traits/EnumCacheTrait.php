<?php

namespace App\Support\Traits;

use Illuminate\Support\Facades\Cache;

trait EnumCacheTrait
{
    /**
     * 获取缓存的key
     */
    public function key($args): string
    {
        return $this->prefix() . ':' . vsprintf($this->value, $args);
    }

    /**
     * 刷新缓存
     */
    public function refresh($args): void
    {
        Cache::delete($this->key($args));
    }

    /**
     * 设置缓存
     */
    public function cacheSet($args, $value, $expire = null): bool
    {
        return Cache::set($this->key($args), $value, $expire ?? $this->getExpire());
    }

    /**
     * 获取当前缓存有效期
     */
    public function getExpire(): int
    {
        $expireExtent = $this->expire();

        if (is_int($expireExtent)) {
            return $expireExtent;
        }

        return rand($expireExtent[0], $expireExtent[1]);
    }

    /**
     * 获取缓存
     */
    public function cacheGet($args): mixed
    {
        return Cache::get($this->key($args));
    }

    /**
     * 缓存数据
     */
    public function cacheData($args, $callback, $expire = null)
    {
        return Cache::remember($this->key($args), $expire ?? $this->getExpire(), $callback);
    }
}

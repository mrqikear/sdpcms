<?php
/**
 * @desc  
 * @author  lixin <lixin.xxx@gmail.com>
 * @date    2018年5月28日
 **/

namespace common\utils;

class CacheUtil{

	CONST KEY_TYPE_COUPON_NOTICE = 'coupon_notice';
	CONST KEY_TYPE_ANNUAL_BILL = 'annual_bill_';
    CONST KEY_TYPE_MALL_RECOMMEND_COMMODITY = 'mall_recommend_commodity_';
    CONST KEY_TYPE_USER_TRACE = 'user_trace_';

    public static function prefix($key){
		 return $_SERVER['HTTP_HOST'].'_'.$key;
	}

	public static function get($key){
		return Yii::$app->redis->getClient()->get(CacheUtil::prefix($key));
	}
	
	public static function set($key, $value){
		return Yii::$app->redis->getClient()->set(CacheUtil::prefix($key), $value);
	}
	public static function lpop($key){
		return Yii::$app->redis->getClient()->lpop(CacheUtil::prefix($key));
	}
	public static function increment($key, $byAmount = 1) {
		return Yii::$app->redis->getClient()->incrBy(CacheUtil::prefix($key), $byAmount);
	}
	
	public static function decrement($key, $byAmount = 1) {
		return Yii::$app->redis->getClient()->decrBy(CacheUtil::prefix($key), $byAmount);
	}
	
	public static function lpush($key, $value){
		return Yii::$app->redis->getClient()->lpush(CacheUtil::prefix($key), $value);
	}
	
	public static function ltrim($key, $start, $stop){
		Yii::$app->redis->getClient()->ltrim(CacheUtil::prefix($key), $start, $stop);
	}

	public static function setex($key, $expire ,$value){
        Yii::$app->redis->getClient()->setex(CacheUtil::prefix($key), $expire, $value);
    }

    public static function expire($key, $expire ){
        Yii::$app->redis->getClient()->expire(CacheUtil::prefix($key), $expire);
    }


    public static function setnx($key, $value){
        return Yii::$app->redis->getClient()->setnx(CacheUtil::prefix($key), $value);
    }

	public static function keys($key){
		return Yii::$app->redis->getClient()->keys(CacheUtil::prefix($key));
	}

	public static function hIncrBy($key,$field,$count)
	{
		return Yii::$app->redis->getClient()->hIncrBy(CacheUtil::prefix($key),$field,$count);
	}

	public static function ttl($key)
	{
		return Yii::$app->redis->getClient()->TTL(CacheUtil::prefix($key));
	}

    public static function incr($key)
    {
        return Yii::$app->redis->getClient()->Incr(CacheUtil::prefix($key));
    }

	public static function multi()
	{
		return Yii::$app->redis->getClient()->multi();
	}

	public static function exec()
	{
		return Yii::$app->redis->getClient()->exec();
	}

	public static function del($key,$is_prefix=true){
		if($is_prefix){
			$key = CacheUtil::prefix($key);
		}
		Yii::$app->redis->getClient()->del($key);
	}

    public static function Rpop($key){
        return Yii::$app->redis->getClient()->Rpop(CacheUtil::prefix($key));
    }

    /**
     * 缓存锁重试次数
     * @var array
     */
    private static $retry_times = [];

    /**
     * 获取缓存数据，带锁
     * @param string $cache_key 数据缓存key
     * @param callable $func 获取数据的执行函数
     * @param int $timeout 数据缓存秒数
     * @param bool $cache_empty 是否缓存空数据
     * @return mixed
     * @throws
     */
    public static function getCacheData($cache_key, $func, $timeout = 3600, $cache_empty = true)
    {
        $data = self::get($cache_key);
        if ($data !== false) {
            if (!is_string($data)) return $data;
            $d = json_decode($data, true);
            if (is_null($d)) return $data;
            return $d;
        }
        if (empty($func) || !is_callable($func)) {
            return $data;
        }
        $retry = $cache_key;
//        if (isCli()) {
//            $retry = "{$cache_key}:" . posix_getpid();
//        }
        $lockKey = "lock:{$cache_key}";
        //初始化重试次数
        if (!isset(self::$retry_times[$retry])) {
            self::$retry_times[$retry] = 0;
        }
        //设置锁
        $lock = self::setnx($lockKey, 1);
        if (!$lock) {
            //最大允许重试200次，防止死循环（$func函数需在2000毫秒内执行完，否则返回null）
            if (self::$retry_times[$retry] >= 200) {
                return null;
            }
            self::$retry_times[$retry]++;
            usleep(10000); //休眠10毫秒
            return self::getCacheData($cache_key, $func, $timeout, $cache_empty);
        }
        //设置锁10秒失效
        self::expire($lockKey, 10);
        $data = call_user_func($func);
        if (empty($data) && !$cache_empty) {
            //删除锁
            self::del($lockKey);
            return $data;
        }
        self::setex($cache_key, $timeout, json_encode($data, JSON_UNESCAPED_UNICODE));
        //删除锁
        self::del($lockKey);
        return $data;
    }
}
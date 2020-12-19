<?php
/**
 * 安全相关的工具
 * @author juwelga
 */

namespace common\utils;

class SecurityUtil {

	public static function getCurrentVersion()
	{
		return 'v8.0.0';
	}

    /**
     * 清理特殊字符，防止sql注入，xss攻击
     * @param type $string 需要处理的参数
     * @return boolean 参数是数组递归执行，直到返回true
     */
    public static function cleanXss(&$string)
    {
        if (!is_array ( $string )){
            $string = trim ( $string );
            $string = strip_tags ( $string );
            if(is_null(json_decode($string))) {
                $string = htmlspecialchars ( $string );
                $string = str_replace ( array ('"', '&quot;', "'", '&acute;'), '', $string );
            }
            // 上传文件文件路径可能带/
            //$string = str_replace ( array ("\\", "/", "..", "../", "./", "//" ), '', $string );
            $no = '/%0[0-8bcef]/';
            $string = preg_replace ( $no, '', $string );
            $no = '/%1[0-9a-f]/';
            $string = preg_replace ( $no, '', $string );
            $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
            $string = preg_replace ( $no, '', $string );

            return true;
        }
        foreach ($string as $value) {
            self::cleanXss ( $value );
        }      
    }


    /**
     *
     * @des md5(md5)
     * @author mrqi
     * @date 2020-12-19 11:39
     */
    public static function md5Md5($password){

        return md5(md5($password));  //返回散列

    }


	/**
	 * 加密方法
	 * @param string $str
	 * @return string
	 */
    public static function encrypt($text,$screct_key){
    	$cipher = 'aes-256-cbc';
    	
    	$ivlen = openssl_cipher_iv_length($cipher);
   		$iv = substr($screct_key, 0, $ivlen);
    	$ciphertext = openssl_encrypt($text, $cipher, $screct_key, $options=0, $iv);
        return base64_encode($ciphertext);
	}

	/**
	 * 解密方法
	 * @param string $str
	 * @return string
	 */
	 public static function decrypt($ciphertext,$screct_key){
		$cipher = 'aes-256-cbc';
    	$ivlen = openssl_cipher_iv_length($cipher);
   		$iv = substr($screct_key, 0, $ivlen);
        $encrypted = openssl_decrypt(base64_decode($ciphertext), $cipher, $screct_key, $options=0, $iv);
        return ($encrypted);
	 }
	 
	 
	 
	 /**
	  * 重复提交频率判断
	  * @param unknown $value
	  * @param number $second
	  * @return boolean
	  */
	 public static function rateLimitByValue($value, $second=2){
	 	$key = self::frequencyLimitKey($value);
		 //不存在
		 if(CacheUtil::setnx($key,1)){
			 CacheUtil::setex($key,$second,1);
			 return false;
		 }
		 return true;
	 }

    /**
     * @description 频率限制， 多少秒只允许一次请求
     * @param $key
     * @param int $second
     * @return bool
     * @create da
     * @author 李昕 <lixin.xxx@gmail.com>
     */
	 //频率限制
	public static function frequencyLimit($key,$second=3)
	{
		/*if(CacheUtil::get($key)){
			return true;
		}
		CacheUtil::setex($key,$second,1);
		return false;*/

		//不存在
		if(CacheUtil::setnx($key,1)){
			CacheUtil::expire($key,$second);
			return false;
		}
		return true;
	}

	//频率限制key
	public static function frequencyLimitKey($value)
	{
		if(is_array($value)){
			$value = json_encode($value);
		}
		if(is_object($value)){
			$value = md5(serialize($value));
		}
		$key = md5($value.Yii::app()->controller->id.Yii::app()->controller->action->id.Yii::app()->params['site_id']);
		return $key;
	}

    /**
     * @description 频率限制， 多少秒只允许一次请求
     * @param $user_id
     * @param $value
     * @param int $second
     * @return bool
     * @create 2019-11-29 14:48:36
     * @author 李昕 <lixin.xxx@gmail.com>
     */
    public static function rateLimitOrder($user_id, $value, $second=2){
        if(is_array($value)){
            $value = json_encode($value);
        }

        $key = md5($value);
        $key = $user_id.'_'.$key;
        if(CacheUtil::get($key)){
        	return true;
		}

		CacheUtil::setex($key,$second,time());
        return false;
    }



    /**
     * @description 清除限制
     * @param $value
     * @create 2019-07-02 12:00:16
     * @author 李昕 <lixin.xxx@gmail.com>
     */
	 public static function clearLimitOrder($user_id, $value){
         if(is_array($value)){
             $value = json_encode($value);
         }

         $key = md5($value);
         return CacheUtil::del($user_id.'_'.$key);
     }

     /*
      * 获取签名
      */
	public static function getSign($params,$open_secret,$is_return_sign=true)
	{
		if(isset($params['sign'])){
			unset($params['sign']);
		}

		if(isset($params['PHPSESSID'])){
			unset($params['PHPSESSID']);
		}
		if(isset($params['s'])){
			unset($params['s']);
		}
		//key升序
		ksort($params);
		$sign = '';

		foreach($params as $k=>$v){
			$sign .= $k.$v;
		}



		$sign .= $open_secret;
		if($is_return_sign){
			return strtoupper(md5($sign));
		}
		return $sign;
	}

    /**
     * OpApi 频率限制，多少秒，多少次
     * @param $key
     * @param $second
     * @return int
     * Creator: ChenTao
     * Date: 2019/10/16
     * Time: 16:19
     */
    public static function  frequencyLimitNum($key,$second,$limit_num)
    {
        $num = CacheUtil::get($key);

        if (!$num){
            CacheUtil::setex($key,$second,1);
            return false;
        }

        if($num < $limit_num){
			$num = CacheUtil::incr($key);
			if($num == 1)
			{
				CacheUtil::expire($key, $second);
			}
            return false;
        }else{

            //增加redis非原子操作容错，防止未加过期时间死锁
            if (CacheUtil::ttl($key) < 0) {
                CacheUtil::expire($key, $second);
            }

            return true;
        }
    }

    /**
     * 加锁
     * @param $key_params
     * @param $func
     * @param string $msg
     * @param int $expire
     * @return mixed
     * @throws SDPException
     * @time 2020-07-07 11:05
     * @author tangxiao@movee.cn
     */
    public static function lock($key_params, $func, $msg = '正在处理中,请稍等', $expire = 5)
    {
        if (empty($func) || !is_callable($func)) {
            throw new SDPException('$func参数必须为回调函数');
        }
        $cache_key = self::frequencyLimitKey($key_params);
        if (self::frequencyLimit($cache_key, $expire)) {
            throw new SDPException($msg);
        }
        try {
            return call_user_func($func);
        } catch (SDPException $e) {
            throw new SDPException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } finally {
            CacheUtil::del($cache_key);
        }
    }
}

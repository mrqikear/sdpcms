<?php
/**
 * 类型检测工具
 * @author juwelga
 */

namespace common\utils;

class TypeCheckUtil {
    /**
     * 修饰符u,模式字符串被当成utf-8
     * 检查用户名：只能由中文，字母和数字组成
     * @param $userName
     */
    public static function checkUserName( $userName ) {
        $pattern = '/^[0-9a-zA-Z\x{4e00}-\x{9fa5}\s]+$/u';
        return preg_match($pattern,trim($userName),$res);
    }

    /**
     * 检查电话号码
     * 数字1,2开头，总共11位
     * @param $phone
     */
    public static function checkPhone( $phone ) {
        $pattern = '/^(([1-2]{1})+\d{10})$/';
        return preg_match($pattern,trim($phone),$res);
    }
    
    /**
     * 检查邮箱格式
     * @param string $email 邮箱地址
     */
    public static function checkEmail($email){
        $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
        return preg_match($pattern, trim($email), $res);
    }
    
     /**
     * 检测是否是整数
     * @param int $num
     */
    public static function checkInt($num) {
        return intval($num)==$num;
    }
    
    /**
     * 检测是否是正整数
     * @param int $num
     */
    public static function checkPosiInt($num) {
        return intval($num)==$num && $num>0;
    }
    
    /**
     * 检测是否是负整数
     * @param int $num
     */
    public static function checkNegInt($num) {
        return intval($num)==$num && $num<0;
    }
    /**
     * 检测是否是非负整数
     * @param int $num
     */
    public static function checkNonNegInt($num) {
        return intval($num)==$num && $num>=0;
    }

    /*
     * 判断数据不是JSON格式
     */
	public static function isNotJson($string){
		return is_null(json_decode($string));
	}

    /*
     * 是否为合法的json数据
     */
	public static function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}

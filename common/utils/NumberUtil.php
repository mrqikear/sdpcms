<?php
/**
 * 数字工具类
 * @author juwelga
 */
namespace common\utils;

class NumberUtil {
    /**
     * 将价格按设置的小数位数输出
     * @param float $price 价格（1.来自订单计算，2.从数据库取出）
     * @param bool
     * @return float 按设置的格式输出（1.保存到数据库，2.用于页面显示）
     */
    protected static $decimal;
    public static function priceDecimal($price, $flag=true,$decimal='') {
        if($decimal === ''){
            if(!self::$decimal){
                self::$decimal=SysConfigService::getInstance()->getConfigValue('price_decimal');
            }
            $decimal = self::$decimal;
        }


        if(empty($price)){
            $price=0;
        }
        $price = round($price,$decimal);
        if($flag || $price - intval($price) != 0){
            return sprintf("%.".$decimal."f",$price);
        }else{
            return $price;
        }

    }

    public static function priceFixedDecimal($price,$decimal=3)
	{
		if(empty($price)){
			$price=0;
		}
		return round($price,$decimal);
	}

    /**
     * 数量格式化
     * @param unknown $price
     */
	public static function numDecimal($num) {
        return sprintf("%.2f",round($num,2));
    }
    /*
     * 相反数
     */
	public static function inverse($number)
	{
		if ($number > 0) {
			return (0 - $number);
		} else {
			return abs($number);
		}
	}
	
	/**
	 * 减法
	 * @param int|float|string $number1
	 * @param int|float|string $number2
	 * @param int $decimal
	 * @return string
	 */
	public static function subtraction($number1, $number2, $decimal = 2){
        if(!is_numeric($number1)){
            $number1 = 0;
        }
        if(!is_numeric($number2)){
            $number2 = 0;
        }
		$multiple = pow(10, $decimal);
		
		$result = $number1 * $multiple - $number2 * $multiple;
		return sprintf("%.".$decimal."f",$result/$multiple);
		
	}
	
	/**
	 * 加法
	 * @param int|float|string $number1
	 * @param int|float|string $number2
	 * @param int $decimal
	 * @return string
	 */
	public static function addition($number1, $number2, $decimal = 2){
        $number1 = floatval($number1);
        $number2 = floatval($number2);

		$multiple = pow(10, $decimal);
		
		$result = $number1 * $multiple + $number2 * $multiple;
		return sprintf("%.".$decimal."f",$result/$multiple);
	}

	/*
	 * 获取单价
	 */
	public static function getUnitPrice($sub_price,$amount)
	{
		if($amount==0){
			return NumberUtil::priceDecimal($amount);
		}
		return NumberUtil::priceDecimal($sub_price/$amount);
	}

    /**
     * 除法
     *
     * @param $divisor
     * @param $dividend
     * @param bool $is_decimal
     * @param bool $flag
     * @param string $decimal
     * @return false|float|int|string
     */
	public static function division($divisor,$dividend,$is_decimal=true, $flag=true,$decimal='')
	{
        $divisor = floatval($divisor);

		if($dividend==0 || floatval($dividend) == 0){
			return NumberUtil::priceDecimal($dividend, $flag, $decimal);
		}
		if($is_decimal){
			return NumberUtil::priceDecimal($divisor/$dividend, $flag, $decimal);
		}
		return $divisor/$dividend;
	}

    /**
     * @description 格式化小数点后零
     * @create 2019-09-24 11:48:38
     * @author 李昕 <lixin.xxx@gmail.com>
     * @param $num
     */
	public static function pointFormat($num){
        $list = explode('.', $num);
        if(empty($list[1]) || empty(floatval($list[1]))){
            return $list[0];
        }

        return $list[0].'.'.rtrim($list[1], 0);
    }

    /**
     * @description 乘法
     * @param $num1
     * @param $num2
     * @param bool $is_decimal
     * @return float|int|string
     * @create 2019-09-24 11:39:58
     * @author 李昕 <lixin.xxx@gmail.com>
     */
	public static function multiplication($num1, $num2,$is_decimal=true, $decimal=''){
        if($is_decimal){
			return NumberUtil::priceDecimal($num1*$num2,$is_decimal,$decimal);
		}
		return $num1*$num2;
    }

	//百分比
	public static function percent($number,$decimal='')
	{
		return NumberUtil::priceDecimal($number*100,true,$decimal).'%';
	}

	/*
	 * 正整数
	 */
	public static function positiveInt($number)
	{

		if(preg_match("/^[1-9][0-9]*$/",$number)){
			return true;
		}
		return false;
	}

	/*
	 * 获取小数位长度
	 */
	public static function getFloatLength($num) {
		$count = 0;

		$temp = explode ( '.', $num );

		if (sizeof ( $temp ) > 1) {
			$decimal = end ( $temp );
			$count = strlen ( $decimal );
		}

		return $count;
	}

	//增加序号
	public static function composeIndex($result,$name='index')
	{
		if(empty($result)){
			return $result;
		}

		$index = 1;
		foreach($result as $key=>$item){
			$result[$key][$name] = $index++;
		}

		return $result;
	}


    /**
     * 验证自然数（含0正整数）
     * @param $number
     * @return bool
     */
    public static function naturalNumber($number)
    {
        if(preg_match("/^([1-9]\d*|[0]{1,1})$/",$number)){
            return true;
        }
        return false;
    }

    /**
     * 格式化小数部分为偶数
     *
     * @param $number
     * @param int $precision
     * @return mixed
     */
    public static function setDecimalToEven($number, $precision = 2)
    {
        $number = static::multiplication($number, 5, true, $precision);
        $number = round($number, $precision - 1);
        $number = static::division($number, 5, true, true, $precision);
        return $number;
    }

    /**
     * 验证带精度数值（无符号数值）
     * @param $number
     * @param int $length_limit
     * @param int $precision
     * @return bool
     */
    public static function isUnsignedDecimal($number, $length_limit = 16, $precision = 2)
    {
        if (preg_match("/^[0-9]{1,{$length_limit}}(.[0-9]{1,{$precision}})?$/", $number)) {
            return true;
        }
        return false;
    }

    /**
     * 去掉小数点后的0
     * Trims the given number.
     *
     * By default bcmath returns numbers with the number of digits according
     * to $scale. This means that bcadd('2', '2', 6) will return '4.00000'.
     * Trimming the number removes the excess zeroes.
     *
     * @param string $number
     *   The number to trim.
     *
     * @return string
     *   The trimmed number.
     */
    public static function trim(string $number) : string {
        if (strpos($number, '.') != FALSE) {
            // The number is decimal, strip trailing zeroes.
            // If no digits remain after the decimal point, strip it as well.
            $number = rtrim($number, '0');
            $number = rtrim($number, '.');
        }

        return $number;
    }
}

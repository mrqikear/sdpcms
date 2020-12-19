<?php
/**
 * Created by PhpStorm.
 * User: narrowsky
 * Date: 19/3/18
 * Time: 上午10:57
 */

namespace common\utils;

class CheckUtil
{
	public static function getMsgByCommodityId($commodity_id,$msg)
	{
		$commodity_info = CommodityDao::model()->findByPk($commodity_id);
		if($commodity_info){
			return $commodity_info['name'].'-'.$commodity_info['unit'].'【'.$msg.'】';
		}
		return $msg;
	}

	public static function isBool($value,$col='')
	{
		if(!in_array($value,[0,1])){
			throw new SDPException($col.'值非法');
		}
	}

	public static function checkCommodityRepeat($commodity_id)
	{
		static $repeat = [];
		if(in_array($commodity_id,$repeat)){
			throw new SDPException(self::getMsgByCommodityId($commodity_id,'重复'));
		}else{
			$repeat[] = $commodity_id;
		}
	}

	public static function checkCommodityInfoRepeat($result,$key='commodity_id')
	{
		if(empty($result)){
			return true;
		}
		$repeat = [];
		foreach($result as $val ){
			if(!isset($val[$key])){
				throw new SDPException($key.'不存在');
			}
			if(in_array($val[$key],$repeat)){
				throw new SDPException(self::getMsgByCommodityId($val[$key],'重复'));
			}else{
				$repeat[] = $val[$key];
			}
		}
	}

	//获取属性类型
	public static function getTypeByValue($value)
	{
		$prefix = strtolower(substr($value, 0, 2));
		//是否为订单号
		if( $prefix == strtolower(TypeConfig::ORDER_PREFIX) ){
			if(strlen($value)>=7){
				return 'order_no';
			}
		}

		if(TypeCheckUtil::checkPhone($value)){
			return 'tel';
		}
		return '';
	}

    /**
     * 验证时间区间最大天数
     *
     * @param $start_date
     * @param $end_date
     * @param string $max_days
     * @throws \SDPException
     */
	public static function checkMaxDay($start_date,$end_date,$max_days='35')
	{
		if(empty($start_date)){
			throw new SDPException('开始日期为空');
		}
		if(empty($end_date)){
			throw new SDPException('结束日期为空');
		}

		$day = DateUtil::timeDiff($start_date,$end_date,'day');

		if($day>$max_days){
			throw new SDPException('最大时间区间为'.$max_days.'天');
		}
	}


    /**
     * 检查参数是否为空
     *
     * @param mixed ...$vars
     * @throws \SDPException
     */
    public static function checkVarsEmpty(...$vars)
    {
        foreach ($vars as $k => $var){
            if (empty($var)){
                throw new SDPException('参数为空');
            }
        }
    }
}
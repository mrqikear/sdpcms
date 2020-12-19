<?php
/**
 * 日期工具类
 * @author juwelga
 */
namespace common\utils;

class DateUtil {
	/**
	 * 将字符串转为日期标准格式Y-m-d
	 * 格式错误时，如果有缺省值，使用缺省值；否则抛异常
	 * 格式正确时：转换为标准格式
	 * @param string $str 待转换字符串
	 * @param date $default 缺省日期(标准格式)
	 * @return date 返回标准日期格式
	 * @throws Exception 格式错误且没有缺省值抛异常
	 */
	public static function str2DateFormat($str,$default=null){
		$date=strtotime($str)?strtotime($str):false;
		if($date===false){//格式错误
			if($default){
				return $default;
			}else{
				throw new SDPException('日期格式非法('.$str.')');
			}
		}else{//格式正确
			return date("Y-m-d",$date);
		}
	}

	public static function dateFormat($str,$default='')
	{
		$date=strtotime($str)?strtotime($str):false;
		if($date === false || $date<0 ){
			return $default;
		}
		return date("Y-m-d",$date);
	}

	public static function dateTimeFormat($str,$default='')
	{
		$date=strtotime($str)?strtotime($str):false;
		if($date === false || $date<0 ){
			if($default){
				return $default;
			}
			throw new SDPException('日期格式非法('.$str.')');
		}
		return date("Y-m-d H:i:s",$date);
	}

    /**
     * 前端传参日期转换
     * @param $date
     * @param int $type 1 为开始时间 00:00:00  2 为结束时间 23:59:59
     * @return string datetime Y-m-d H:i:s
     * @throws SDPException
     * @author FangCai <zilgoup@gmail.com>
     * @datetime 2020/6/9 11:49 上午
     */
	public static function filterDate($date, $type = 1) {
        $suffix = '';
	    if (empty($date)) {
	        throw new SDPException("时间参数为空");
        }

	    $date_time = DateTime::createFromFormat("Y-m-d", $date);

	    if ($date_time === false) {
	        throw new SDPException("非法的日期格式");
        }

	    if ($type == 1) {
	        $suffix = ' 00:00:00';
        }

	    if ($type == 2) {
	        $suffix = ' 23:59:59';
        }

	    $time = $date_time->getTimestamp();

	    return date('Y-m-d', $time).$suffix;
    }

    /**
     * 日期转时间戳
     * @param $date_time
     * @param string $format
     * @return int
     * @throws SDPException
     * @author FangCai <zilgoup@gmail.com>
     * @datetime 2020/10/16 10:49 上午
     */
    public static function dateToTimeStamp($date_time, $format="Y-m-d H:i:s") {
        if (empty($date_time)) {
            throw new SDPException("时间参数为空");
        }

        $date_time = DateTime::createFromFormat($format, $date_time);

        if ($date_time === false) {
            throw new SDPException("非法的日期格式");
        }

        return $date_time->getTimestamp();
    }

	/**
	 * 获取今天的日期
	 *
	 * @return string 年-月-日
	 */
	public static function getToday() {
		return date ( 'Y-m-d', time () );
	}


	//获取当前时间
	public static function getNow()
	{
		return date('Y-m-d H:i:s',time());
	}

	//获取当前时间戳
	public static function getTimestamp()
	{
		return strtotime(self::getNow());
	}

	/*
	 * 获取过期日期
	 * $production_date 生产日期
	 * $shelf_life 保质期(天)
	 */
	public static function getExpiredDate($production_date,$shelf_life)
	{
		$production_date = strtotime($production_date);

		$expire_date = strtotime("+".$shelf_life." day", $production_date);

		return date('Y-m-d',$expire_date);
	}

	/**
	 * 获取昨天的日期
	 *
	 * @return string 年-月-日
	 */
	public static function getYesterday() {
		return date ( 'Y-m-d', strtotime ( '-1 day' ) );
	}

	/**
	 * 获取明天的日期
	 *
	 * @return string 年-月-日
	 */
	public static function getTomorrow() {
		return date ( 'Y-m-d', strtotime ( '+1 day' ) );
	}

    /**
     * 获取前天的日期
     *
     * @return string 年-月-日
     */
    public static function getTwoDays() {
        return date ( 'Y-m-d', strtotime ( '+2 day' ) );
    }

	/**
	 * 获取下一天的日期
	 *
	 * @param string $day
	 *        	要计算的日期
	 * @return string 下一天的日期
	 */
	public static function getNextDay($day) {
		return date ( 'Y-m-d', strtotime ( $day ) + 24 * 3600 );
	}

    /**
     * 获取上一天的日期
     *
     * @param string $day
     *        	要计算的日期
     * @return string 下一天的日期
     */
    public static function getLastDay($day) {
        return date ( 'Y-m-d', strtotime ( $day ) - 24 * 3600 );
    }
	/**
	 * 获取本周的起止日期
	 *
	 * @return array ["start"=>本周一的日期,"end"=>本周日的日期]
	 */
	public static function getThisWeek() {
		$start = date ( "Y-m-d", strtotime ( "this week" ) );
		$end = date ( "Y-m-d", strtotime ( "this week +6 days" ) );
		return [
			"start" => $start,
			"end" => $end
		];
	}

	/**
	 * 获取最近7天的起止日期
	 *
	 * @return array ["start"=>倒数第七天的日期,"end"=>今天的日期]
	 */
	public static function getLastWeek() {
		$start = date ( "Y-m-d", strtotime ( '-6 day' ) );
		$end = date ( 'Y-m-d', time () );
		return [
			"start" => $start,
			"end" => $end
		];
	}

	/**
	 * 获取本月的起止日期
	 *
	 * @return array ["start"=>本月1日的日期,"end"=>本月最后一天的日期]
	 */
	public static function getThisMonth($date='') {
		if(empty($date)){
			$date = time();
		}else{
			$date = strtotime($date);
		}
		$start = date ( 'Y-m-01', $date);
		$end = date ( 'Y-m-d', strtotime ( "$start +1 month -1 day" ) );
		return [
			"start" => $start,
			"end" => $end
		];
	}

	/**
	 * 获取最近30天的起止日期
	 *
	 * @return array ["start"=>倒数第30天的日期,"end"=>今天的日期]
	 */
	public static function getLastMonth() {
		$start = date ( "Y-m-d", strtotime ( '-29 day' ) );
		$end = date ( 'Y-m-d', time () );
		return [
			"start" => $start,
			"end" => $end
		];
	}

	/**
	 * 获取起止时间的间隔天数
	 *
	 * @param string $start
	 *        	开始时间("年-月-日","年-月-日 时:分:秒")
	 * @param string $end
	 *        	截止时间("年-月-日","年-月-日 时:分:秒")
	 * @return int 间隔天数
	 */
	public static function getDaysBetweenTwoTime($start, $end) {
		return floor ( (strtotime ( $end ) - strtotime ( $start )) / 86400 );
	}

	/**
	 * 微秒数
	 * @return number
	 */
	public static function  microtime_float() {
		list ( $usec, $sec ) = explode ( " ", microtime () );
		return (( float ) $usec + ( float ) $sec);
	}

	/*
	 * 时间差
	 */
	public static function timeDiff( $begin_time, $end_time,$attr='')
	{
		if( $begin_time > $end_time ){
			$result = array( "day" => 0, "hour" => 0, "min" => 0, "sec" => 0 );
			if($attr){
				if(isset($result[$attr])){
					return $result[$attr];
				}
				return $result;
			}else{
				return $result;
			}
		}
		$timediff = strtotime($end_time) - strtotime($begin_time);
		$days = intval( $timediff / 86400 );
		$remain = $timediff % 86400;
		$hours = intval( $remain / 3600 );
		$remain = $remain % 3600;
		$mins = intval( $remain / 60 );
		$secs = $remain % 60;
		$result = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
		if($attr){
			if(isset($result[$attr])){
				return $result[$attr]+1;
			}
			return $result;
		}else{
			return $result;
		}
	}

	/*
	 * 相差秒数
	 */
	public static function timeDiffSec($begin_time,$end_time )
	{
		$res = self::timeDiff($begin_time,$end_time);
		return $res['sec'] + $res['min']*60 + $res['hour']*60*60 + $res['day']*60*60*24;
	}
	/**
	 * 将时间转为 xx分钟前， xx小时前
	 */
	public static function timeFormat($time){
		$difftime = time() - $time;
		if($difftime >= 3600 * 24){
			return date('Y-m-d H:i', $time);
		}

		if($difftime >= 3600){
			return intval($difftime/3600).'小时前';
		}

		if($difftime >= 60){
			return intval($difftime/60).'分钟前';
		}

		return '刚刚';
	}

	//验证Excel日期格式是否非法
	public static function isExcelDate($date)
	{
		if(is_float($date)){
			return true;
		}else{
			return false;
		}
	}

	/*
	 * 获取最近几天起止时间
	 */
	public static function getLatelyDay($j)
	{
		$result = array();
		for(;$j>=0;$j--){
			$date = date("Y-m-d",strtotime("-{$j} Day"));
			$result[] = [
				'desc'       => $date,
				'start_date' => $date,
				'end_date'   => $date,
				'start_time' => $date.' 00:00:00',
				'end_time'   => $date.' 23:59:59'
			];
		}
		return $result;
	}

	/*
	 * 获取最近几周起止时间
	 */
	public static function getLatelyWeek($j)
	{
		$result = array();
		for(;$j>=0;$j--){
			$end_date = date("Y-m-d",strtotime("-{$j} week Sunday"));
			$s = $j+1;
			$start_date = date("Y-m-d",strtotime("-{$s} week Monday"));
			$result[] = [
				'desc'       => $start_date.'~'.$end_date,
				'start_date' => $start_date,
				'end_date'   => $end_date,
				'start_time' => $start_date.' 00:00:00',
				'end_time'   => $end_date.' 23:59:59'
			];
		}
		return $result;
	}

	/*
	 * 获取最近几月起止时间
	 */
	public static function getLatelyMonth($j)
	{
		$result = array();
		for(;$j>=0;$j--){
			$time = date('Y-m' , time());
			$start_date = date('Y-m-01' , strtotime("-{$j} month" , strtotime($time)));
			$end_date = date('Y-m-t' , strtotime("-{$j} month" , strtotime($time)));
			$result[] = [
				'desc'       => date('Y-m' , strtotime("-{$j} month" , strtotime($time))),
				'start_date' => $start_date,
				'end_date'   => $end_date,
				'start_time' => $start_date.' 00:00:00',
				'end_time'   => $end_date.' 23:59:59'
			];
		}
		return $result;
	}

	/**
	 * 计算两个时间段是否有交集
	 *
	 * @param string $beginTime1 开始时间1
	 * @param string $endTime1 结束时间1
	 * @param string $beginTime2 开始时间2
	 * @param string $endTime2 结束时间2
	 * @return bool
	 */
	public static function isTimeCross($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '')
	{
		$status = $beginTime2-$beginTime1;
		if ($status >= 0) {
			$status2 = $beginTime2 - $endTime1;
			if ($status2 >= 0) {
				return false;
			} else {
				return true;
			}
		} else {
			$status2 = $endTime2 - $beginTime1;
			if ($status2 >= 0) {
				return true;
			} else {
				return false;
			}
		}
	}

	//获取时段

    /**
     * 获取本周所有日期
     */
    public static function getWeekDetail(){
        $time = time();
        //获取当前周几
        $week = date('w', $time);
        $result = [];
        for ($i=1; $i<=7; $i++){
            $date = date('Y-m-d' ,strtotime( '+' . $i-$week .' days', $time));
            $result[] = [
                'desc'       => $date,
                'start_date' => $date,
                'end_date'   => $date,
                'start_time' => $date.' 00:00:00',
                'end_time'   => $date.' 23:59:59'
            ];
        }
        return $result;
    }

    /**
     * 获取本月所有日期
     */
    public static function getMothDetail()
    {
        $j = date("t"); //获取当前月份天数
        $start_time = strtotime(date('Y-m-01'));  //获取本月第一天时间戳
        $result = array();
        for($i=0;$i<$j;$i++){
            $date = date('Y-m-d',$start_time+$i*86400); //每隔一天赋值给数组
            $result[] = [
                'desc'       => $date,
                'start_date' => $date,
                'end_date'   => $date,
                'start_time' => $date.' 00:00:00',
                'end_time'   => $date.' 23:59:59'
            ];
        }
        return $result;
    }

    //获取时段
	public static function getDatePeriod($start,$end)
	{
		$result = [];
		for($i=$start;$i<=$end;$i++){
			$result[] = self::getSomeDay($i);
		}

		return $result;
	}

	//获取莫一天
	public static function getSomeDay($day)
	{
		return date('Y-m-d',strtotime('+'.$day.'day'));
	}

    //获取最近时间
    public static function getLatestDate($date_type = 3)
    {
        switch ($date_type){
            case '1':
                return  date ( "Y-m-d", strtotime ( '-7 day' ) );
                break;
            case '2':
                return  date ( "Y-m-d", strtotime ( '-30 day' ) );
                break;
            case '3':
                return  date ( "Y-m-d", strtotime ( '-90 day' ) );
                break;
            default:
                return '';
                break;
        }
    }

    public static function getWeekName($week)
    {
        $week_arr = [
            '周日',
            '周一',
            '周二',
            '周三',
            '周四',
            '周五',
            '周六'
        ];
        // 如果传入的是数值，进行判断返回
        if (!is_array($week)){
            if ( (int)$week > 6 || (int)$week < 0){
                return false;
            }
            return $week[$week];
        }
        // 根据传入的数组，返回对应的周几字符串
        $str = '';
        foreach ($week_arr as $key => $value)
        {
            if (in_array($key, $week)){
                $str .= $value.'、';
            }
        }
        return substr($str, 0, -3);
    }
}

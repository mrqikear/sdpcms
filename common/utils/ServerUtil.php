<?php
/**
 * 服务器工具类
 * 不知道谁写的，有些方法没注释，不知道干什么的
 * 全部拿过来，避免报错
 */

namespace common\utils;

class ServerUtil {

    /**
     * @return bool|string 不允许返回ip为localhost
     * @todo 修正getClientIP(),配置代理服务器，以方便获取IP
     */
    public static function  getClientIP()
    {
        $ip = "unknown";
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip = trim($ip);
        if ('127.0.0.1' == $ip || '::1' == $ip) {
            return false;
        }
        return $ip;
    }

    /**
     * 允许返回值为127.0.0.1
     * @return string
     */
    public static function getClientIPAllowLocalhost()
    {
        $ip = "unknown";
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip = trim($ip);
        return $ip;
    }

    /**
     * @param string $remote_ip 远端IP,即为登录者IP
     * @param int $login_result_code 对应的登录类型code, TypeConfig::
     * @param int $user_type_code 对应的user_type
     * @see TypeConfig::$LOG_LOGIN_RESULT_SUCCESS
     * @see TypeConfig::$LOG_LOGIN_TYPE_SUPER_ADMIN
     */
    public static function serverLog($remote_ip, $login_result_code, $user_type_code)
    {
        $model = array();
        $model['content'] = $remote_ip;
        $model['source_id'] = $user_type_code;
        $model['source_type'] = 2;

        switch ($login_result_code) {
            case -1:
                $model['type'] = -1;    // failed to login
                break;
            case 0:
                $model['type'] = 0;     // succeed login
                break;
            default:
                $model['type'] = -2;     // unknown
        }
        $log_model = new Log();
        $log_model->setAttributes($model);

        if (!$log_model->save()) {
            error_log('log failed.' . __FILE__ . __LINE__);
        }
    }

    public static function superAdminLog($content, $opStatus, $relateData, $controllerId, $actionId) {
        $uid = self::adminUserId();
        $remoteIp = ServerUtil::getClientIPAllowLocalhost();
        $model = new Log();
        $model->setAttributes([
            'content' => $content,
            'source_id' => $uid,
            'type' => 3,
            'op_status' => $opStatus,
            'ip' => $remoteIp,
            'relate_data' => $relateData,
            'controller_id' => strtolower($controllerId),
            'action_id' => strtolower($actionId),
            'create_time' => date('Y-m-d H:i:s'),
        ]);
        if (!$model->save()) {
            error_log('log failed.' . __FILE__ . __LINE__);
        }
    }

	/**
	 * 获取登录用户信息
	 */
    public static function adminUserDic() {
        $list = SuperUser::model()->findAll();
        return CHtml::listData($list, 'id', 'user_name');
    }

	/**
	 * 获取登录用户名
	 */
    public static function adminUsername() {
        $username = Yii::app()->session['superadmin'];
        return $username;
    }

	/**
	 * 获取登录用户id
	 */
    public static function adminUserId() {
        $uid = isset(Yii::app()->session)&&Yii::app()->session['id']?Yii::app()->session['id']:0;
        return $uid;
	}

	/**
	 * 检查用户登录状态
	 */
	public static function isLogin() {
		return Yii::app()->session['superadmin'] ? true : false;
	}

    /**
    * 将一个二维数组按照指定字段的值分组
    *
    * 用法：
    * @code php
    * $rows = array(
    *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
    *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
    *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
    *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
    *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
    *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
    * );
    * $values = ArrayHelper::groupBy($rows, 'parent');
    *
    * dump($values);
    *   // 按照 parent 分组的输出结果为
    *   // array(
    *   //   1 => array(
    *   //        array('id' => 1, 'value' => '1-1', 'parent' => 1),
    *   //        array('id' => 2, 'value' => '2-1', 'parent' => 1),
    *   //        array('id' => 3, 'value' => '3-1', 'parent' => 1),
    *   //   ),
    *   //   2 => array(
    *   //        array('id' => 4, 'value' => '4-1', 'parent' => 2),
    *   //        array('id' => 5, 'value' => '5-1', 'parent' => 2),
    *   //   ),
    *   //   3 => array(
    *   //        array('id' => 6, 'value' => '6-1', 'parent' => 3),
    *   //   ),
    *   // )
    * @endcode
    *
    * @param array $arr 数据源
    * @param string $keyField 作为分组依据的键名
    *
    * @return array 分组后的结果
    */
   static function groupBy($arr, $keyField)
   {
           $ret = array();
           foreach ($arr as $row)
           {
                   $key = $row[$keyField];
                   $ret[$key][] = $row;
           }
           return $ret;
   }

    /**
     * 将一个二维数组按照指定字段keyGroup的值分组,并且按指定字段keySort的值排序
     * @param array $arr 数据源
     * @param string $keyGroup 作为分组依据的键名
     * @param string $keySort 作为排序依据的键名
     * @return array 分组排序后的结果
     */
   static function groupAndSort($arr,$keyGroup,$keySort){
        $ret = [];
        foreach ($arr as $row)
        {
            $group = $row[$keyGroup];
            $sort = $row[$keySort];
            if(!isset($ret[$group])){
                $ret[$group]=[];
            }
            $ret[$group][$sort] = $row;

        }
        foreach ($ret as &$row) {
            array_values($row);
        }
        return $ret;
   }
   /* * @endcode
    *
    * @param array $arr 数据源
    * @param string $keyField 作为分组依据的键名
    *
    * @return array 分组后的结果
    */
   public static function groupByKey($models, $groupField,  $keyField = '')
   {
        $ret = array();
        foreach($models as $model)
        {
            $group=self::value($model,$groupField);
            if($keyField != '') {
                $value=self::value($model,$keyField);
                $ret[$group][$value]=$model;
            }
            else
                $ret[$group]=$model;
        }
       return $ret;
   }


   static function groupByUnique($arr, $keyField)
   {
           $ret = array();
           foreach ($arr as $row)
           {
                   $key = $row[$keyField];
                   $ret[$key] = $row;
           }
           return $ret;
   }
   public static function value($model,$attribute,$defaultValue=null)
    {
        if(is_scalar($attribute) || $attribute===null)
            foreach(explode('.',$attribute) as $name)
            {
                if(is_object($model) && isset($model->$name))
                    $model=$model->$name;
                elseif(is_array($model) && isset($model[$name]))
                    $model=$model[$name];
                else
                    return $defaultValue;
            }
        else
            return call_user_func($attribute,$model);

        return $model;
    }

     public static   function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=1){

        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if($unit==2){
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }

    //获取固定https域名
    public static function getHttpsDomain()
	{
		$domain = Yii::app()->request->hostInfo;
		$domain = explode('//',$domain);
		if(isset($domain[1])){
			return 'https://'.$domain[1];
		}
		return Yii::app()->request->hostInfo;
	}

	//获取固定http
	public static function getHttpDomain($url)
	{
		$domain = explode('//',$url);
		if(isset($domain[1])){
			return 'http://'.$domain[1];
		}
		return $url;
	}

	//获取域名
	public static function getDomain()
	{
		return Yii::app()->request->hostInfo;
	}

    /**
     * 获取域名 不带http
     *
     * @return mixed
     */
    public static function getDomainName()
    {
        $domain = explode('//', Yii::app()->request->hostInfo);
        return $domain[1] ?: 'undefined-project--domain';
    }

    /**
     * 获取base系统环境  变量
     *
     * @return string
     */
    public static function getBaseSystemEnv()
    {
        return getenv('BASE_SYSTEM_ENV') ?: 'dev';
    }

    /**
     * 获取aliyun 日志服务AccessId
     *
     * @return string
     */
    public static function getAliyunLogAccessId()
    {
        return getenv('ALIYUN_LOG_ACCESS_ID');
    }

    /**
     * 获取aliyun 日志服务AccessKey
     *
     * @return string
     */
    public static function getAliyunLogAccessKey()
    {
        return getenv('ALIYUN_LOG_ACCESS_KEY');
    }

	public static function getRenameExcelPath($template_filename,$rename='')
	{
		$source_path = Yii::app()->basePath.'/../www/word/Template/'.$template_filename.'.xlsx';

		$host = explode('.', $_SERVER['HTTP_HOST']);
		$rename = $rename.'_'.$host[0].date('YmdHis').rand(1000,9999).'.xlsx';
		$target_path = Yii::app()->basePath.'/../www/word/Result/'.$rename;
		copy($source_path,$target_path);
		return '/word/Result/'.$rename;
	}

}

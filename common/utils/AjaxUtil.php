<?php
namespace common\utils;
use Yii;
use yii\helpers\Json;

/**
 * 异步回调工具类
 * @author juwelga
 */
class AjaxUtil {
    /**
     * 成功状态
     */
    const STATUS_SUCCESS=1;
    /**
     * 失败状态
     */
    CONST STATUS_FAIL=0;
    
    
    CONST CODE_UNLOGIN = 1;
    CONST CODE_UNPOWER = 2;
    /**
     * 输出Ajax数据
     * 所有controller和监听钩子会调用
     * @param type data 输出数据,可能是数组，字符串，数字
     * ]
     */
    public static function render($data=null,$message=null){
        header('Content-type: application/json');
        echo Json::encode([
            'status' => self::STATUS_SUCCESS,
            'data' => $data,
            'message' => $message
        ]);

        Yii::$app->end();
    }
    
	/**
     * 是否是api请求
     */
    public static function isApiRequest(){
    	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest'){
    		return true;
    	}
    	
    	$is_api = Yii::$app->getRequest()->getParam('is_api');
    	if($is_api){
    		return true;
    	}
    	return false;
    }
    
    /**
     * 输出Ajax错误
     * 所有controller和监听钩子会调用
     * @param string message 错误信息
     * @param int errCode 错误代码
     */
    public static function error($message,$errCode=0,$data=array()){
        header('Content-type: application/json'); 
        echo Json::encode([
            'status' => self::STATUS_FAIL,            
            'message' => $message,
            'errCode' => $errCode,
            'data'    => $data
        ]);
        Yii::$app->end();
    }
}

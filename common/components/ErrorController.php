<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 15:31
 */

namespace common\components;


use Yii;
use yii\helpers\Json;

class ErrorController extends \yii\web\ErrorHandler{

//    protected function renderException($exception){
//
//        //todo 业务处理异常
//
//            echo  Json::encode([
//                'message'=>$exception->getMessage(),
//                'code'=>$exception->getCode() == 0 ? 500 :$exception->getCode(),
//            ]);
//    }


}
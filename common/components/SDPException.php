<?php
namespace common\components;
use yii\base\Exception;

/**
 * @desc
 * @author  lixin <lixin.xxx@gmail.com>
 * @date    2018年1月23日
 **/
class SDPException extends Exception {

    public function __construct($message = '' , $code = 0){
        parent::__construct($message , $code);
    }
}
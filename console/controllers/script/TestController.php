<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/11/13
 * Time: 9:52
 */

namespace console\controllers\script;
use Yii;
use yii\console\Controller;
use Fizzday\OcrPHP\OcrPHP;

class TestController extends Controller{

//    public function actionTest(){
//        echo 33;die;
//    }


    public function actionTest(){
        $file = __DIR__.'/test.jpg';
        #echo  $file;die;
        if (!file_exists($file)) die('file not exists');
        echo OcrPHP::file($file)->run('id');
        echo PHP_EOL;
        echo OcrPHP::file($file)->lang('ch_sim')->run();
    }
}
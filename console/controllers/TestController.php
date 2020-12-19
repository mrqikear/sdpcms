<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/11/13
 * Time: 9:53
 */

namespace console\controllers;
use Yii;
use yii\console\Controller;


class TestController extends Controller{

    public function actionTest(){
        echo 33;die;
    }
}
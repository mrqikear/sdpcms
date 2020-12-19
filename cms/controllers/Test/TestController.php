<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 16:19
 */
namespace cms\controllers\Test;
use common\components\sdpBaseController;

class TestController extends sdpBaseController
{


    /**
     * @url /Test/test/test
     * @des
     * @author mrqi
     * @date 2020-12-18 16:23
     */
    public function actionTest(){
        echo 4444;die;
    }

}
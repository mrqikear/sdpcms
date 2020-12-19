<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 10:04
 */

namespace cms\controllers;

use cms\services\UserServices;
use cms\tools\PowerCheck;
use common\components\sdpBaseController;
use common\components\SDPException;
use common\utils\AjaxUtil;


class UserController extends sdpBaseController
{

    /**
     * @url user/sginup
     * @des 注册接口
     * @log
     * @author mrqi
     * @date 2020-12-18 14:36
     */

    public function actionSginup(){
        $user_name = $this->postParam('user_name');
        $password  = $this->postParam('password');
        try {
            $res=UserServices::getInstance()->signUp($user_name, $password);
            AjaxUtil::render($res,'注册成功');
        }catch (SDPException $exception){
            throw  new SDPException($exception->getMessage(),$exception->getCode());
        }
    }



    /**
     * @des
     * @author mrqi
     * @date 2020-12-18 17:53
     */
    public function actionTest(){

        PowerCheck::isLogin("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjJSSGREc1hveGsifQ.eyJpc3MiOiJodHRwOlwvXC9zZHBjbXMuY29tIiwiYXVkIjoiIiwianRpIjoiMlJIZERzWG94ayIsImlhdCI6MTYwODM2MjE3NiwibmJmIjoxNjA4MzYyMTc2LCJleHAiOjE2MDgzNjU3NzYsInVzZXIiOiJtcnFpIiwiaWQiOiJtcnFpIn0.i8JFaAJHhfseqISVCbv3dB_pdGIbtHfmLdBjOpR-sYs");
    }

}